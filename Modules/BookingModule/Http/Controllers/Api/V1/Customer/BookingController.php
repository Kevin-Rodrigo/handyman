<?php

namespace Modules\BookingModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\BidModule\Entities\PostBid;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Entities\User;
use Modules\BookingModule\Entities\Booking;
use Modules\PaymentModule\Entities\OfflinePayment;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\CustomerModule\Traits\CustomerAddressTrait;
use Modules\BookingModule\Entities\BookingStatusHistory;
use Modules\BidModule\Http\Controllers\APi\V1\Customer\PostBidController;

class BookingController extends Controller
{
    use BookingTrait, CustomerAddressTrait;
    private Booking $booking;
    private BookingStatusHistory $booking_status_history;

    protected OfflinePayment $offline_payment;
    private bool $is_customer_logged_in;
    private mixed $customer_user_id;

    public function __construct(Booking $booking, BookingStatusHistory $booking_status_history, Request $request, OfflinePayment $offline_payment)
    {
        $this->booking = $booking;
        $this->booking_status_history = $booking_status_history;
        $this->offline_payment = $offline_payment;

        $this->is_customer_logged_in = (bool)auth('api')->user();
        $this->customer_user_id = $this->is_customer_logged_in ? auth('api')->user()->id : $request['guest_id'];
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function place_request(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|in:' . implode(',', array_column(PAYMENT_METHODS, 'key')),
            'zone_id' => 'required|uuid',
            'service_schedule' => 'required|date',
            'service_address_id' => is_null($request['service_address']) ? 'required' : 'nullable',

            //For bidding
            'post_id' => 'nullable|uuid',
            'provider_id' => 'nullable|uuid',

            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
            'offline_payment_id' => 'required_if:payment_method,offline_payment',
            'customer_information' => 'required_if:payment_method,offline_payment',
            'service_address' => is_null($request['service_address_id']) ? [
                'required',
                'json',
                function ($attribute, $value, $fail) {
                    $decoded = json_decode($value, true);

                    if (json_last_error() !== JSON_ERROR_NONE) {
                        $fail($attribute.' must be a valid JSON string.');
                        return;
                    }

                    if (is_null($decoded['lat']) || $decoded['lat'] == '') $fail($attribute.' must contain "lat" properties.');
                    if (is_null($decoded['lon']) || $decoded['lon'] == '') $fail($attribute.' must contain "lon" properties.');
                    if (is_null($decoded['address']) || $decoded['address'] == '') $fail($attribute.' must contain "address" properties.');
                    if (is_null($decoded['contact_person_name']) || $decoded['contact_person_name'] == '') $fail($attribute.' must contain "contact_person_name" properties.');
                    if (is_null($decoded['contact_person_number']) || $decoded['contact_person_number'] == '') $fail($attribute.' must contain "contact_person_number" properties.');
                    if (is_null($decoded['address_label']) || $decoded['address_label'] == '') $fail($attribute.' must contain "address_label" properties.');
                },
            ] : '',

            'is_partial' => 'nullable|in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //offline payment validation
        if ($request['payment_method'] == 'offline_payment') {
            $offline_payment_data = $this->offline_payment->find($request['offline_payment_id']);
            $fields = array_column($offline_payment_data->customer_information, 'field_name');
            $customer_information = (array)json_decode(base64_decode($request['customer_information']))[0];

            foreach ($fields as $field) {
                if (!key_exists($field, $customer_information)) {
                    return response()->json(response_formatter(DEFAULT_400, $fields, null), 400);
                }
            }
        }

        $customer_user_id = $this->customer_user_id;
        //service address create (if no saved address)
        if (is_null($request['service_address_id'])) {
            $request['service_address_id'] = $this->add_address(json_decode($request['service_address']), null, !$this->is_customer_logged_in);
        }

        $min_booking_amount = (float)(business_config('min_booking_amount', 'booking_setup'))?->live_values;
        $total_booking_amount = cart_total($customer_user_id);

        if (!isset($request['post_id']) && $min_booking_amount > 0 && $total_booking_amount < $min_booking_amount) {
            return response()->json(response_formatter(MINIMUM_BOOKING_AMOUNT_200), 200);
        }

        //wallet
        if ($request['payment_method'] == 'wallet_payment') {
            if (!isset($request['post_id'])) {
                //normal
                $response = $this->place_booking_request($customer_user_id, $request, 'wallet_payment');
            } else {
                //for bidding
                $post_bid = PostBid::with(['post'])
                    ->where('post_id', $request['post_id'])
                    ->where('provider_id', $request['provider_id'])
                    ->first();

                $data = [
                    'payment_method' => $request['payment_method'],
                    'zone_id' => $request['zone_id'],
                    'service_tax' => $post_bid?->post?->service?->tax,
                    'provider_id' => $post_bid->provider_id,
                    'price' => $post_bid->offered_price,
                    'service_schedule' => !is_null($request['booking_schedule']) ? $request['booking_schedule'] : $post_bid->post->booking_schedule,
                    'service_id' => $post_bid->post->service_id,
                    'category_id' => $post_bid->post->category_id,
                    'sub_category_id' => $post_bid->post->category_id,
                    'service_address_id' => !is_null($request['service_address_id']) ? $request['service_address_id'] : $post_bid->post->service_address_id,
                    'is_partial' => $request['is_partial']
                ];

                $user = User::find($customer_user_id);
                $tax = !is_null($data['service_tax']) ? round((($data['price'] * $data['service_tax']) / 100) * 1, 2) : 0;
                if (isset($user) && $user->wallet_balance < ($post_bid->offered_price + $tax)) {
                    return response()->json(response_formatter(INSUFFICIENT_WALLET_BALANCE_400), 400);
                }

                $response = $this->place_booking_request_for_bidding($customer_user_id, $request, 'wallet_payment', $data);

                if ($response['flag'] == 'success') {
                    PostBidController::accept_post_bid_offer($post_bid->id, $response['booking_id']);
                }
            }

        }
        //offline
        elseif ($request['payment_method'] == 'offline_payment') {
            if (!isset($request['post_id'])) {
                //normal
                $response = $this->place_booking_request($customer_user_id, $request, 'offline-payment', !$this->is_customer_logged_in);
            } else {
                //for bidding
                $post_bid = PostBid::with(['post'])
                    ->where('post_id', $request['post_id'])
                    ->where('provider_id', $request['provider_id'])
                    ->first();

                $data = [
                    'payment_method' => $request['payment_method'],
                    'zone_id' => $request['zone_id'],
                    'service_tax' => $post_bid?->post?->service?->tax,
                    'provider_id' => $post_bid->provider_id,
                    'price' => $post_bid->offered_price,
                    'service_schedule' => !is_null($request['booking_schedule']) ? $request['booking_schedule'] : $post_bid->post->booking_schedule,
                    'service_id' => $post_bid->post->service_id,
                    'category_id' => $post_bid->post->category_id,
                    'sub_category_id' => $post_bid->post->category_id,
                    'service_address_id' => !is_null($request['service_address_id']) ? $request['service_address_id'] : $post_bid->post->service_address_id,
                    'is_partial' => $request['is_partial']
                ];

                $response = $this->place_booking_request_for_bidding($customer_user_id, $request, 'offline_payment', $data);

                if ($response['flag'] == 'success') {
                    PostBidController::accept_post_bid_offer($post_bid->id, $response['booking_id']);
                }
            }
        }
        //normal
        else {
            $response = $this->place_booking_request($customer_user_id, $request, 'cash-payment', !$this->is_customer_logged_in);
        }

        if ($response['flag'] == 'success') {
            return response()->json(response_formatter(BOOKING_PLACE_SUCCESS_200, $response), 200);
        } else {
            return response()->json(response_formatter(BOOKING_PLACE_FAIL_200), 200);
        }
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'booking_status' => 'required|in:all,' . implode(',', array_column(BOOKING_STATUSES, 'key')),
            'string' => 'string'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bookings = $this->booking->with(['customer'])->where(['customer_id' => $request->user()->id])
            ->when($request->has('string'), function ($query) use ($request) {
                $keys = explode(' ', base64_decode($request['string']));
                foreach ($keys as $key) {
                    $query->orWhere('id', 'LIKE', '%' . $key . '%');
                }
            })
            ->when($request['booking_status'] != 'all', function ($query) use ($request) {
                return $query->ofBookingStatus($request['booking_status']);
            })
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $bookings), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function show(Request $request, string $id): JsonResponse
    {
        $booking = $this->booking->where(['customer_id' => $request->user()->id])->with([
            'detail.service', 'schedule_histories.user', 'status_histories.user', 'service_address', 'customer', 'provider', 'zone', 'serviceman.user', 'booking_partial_payments'
        ])->where(['id' => $id])->first();
        if (isset($booking)) {
            return response()->json(response_formatter(DEFAULT_200, $booking), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function track(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking
            ->with(['detail.service', 'schedule_histories.user', 'status_histories.user', 'service_address', 'customer', 'provider', 'zone', 'serviceman.user'])
            ->where(['readable_id' => $id])
            ->whereHas('service_address', fn ($query) => $query->where('contact_person_number', $request['phone']))
            ->first();

        if (isset($booking)) return response()->json(response_formatter(DEFAULT_200, $booking), 200);

        return response()->json(response_formatter(DEFAULT_404, $booking), 404);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $booking_id
     * @return JsonResponse
     */
    public function status_update(Request $request, string $booking_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_status' => 'required|in:canceled',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->where('id', $booking_id)->where('customer_id', $request->user()->id)->first();

        if (isset($booking)) {
            $booking->booking_status = $request['booking_status'];

            $booking_status_history = $this->booking_status_history;
            $booking_status_history->booking_id = $booking_id;
            $booking_status_history->changed_by = $request->user()->id;
            $booking_status_history->booking_status = $request['booking_status'];

            DB::transaction(function () use ($booking_status_history, $booking) {
                $booking->save();
                $booking_status_history->save();
            });

            return response()->json(response_formatter(DEFAULT_200, $booking), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }


}
