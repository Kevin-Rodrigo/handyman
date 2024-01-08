<?php

namespace Modules\BookingModule\Http\Controllers\Api\V1\Serviceman;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingDetail;
use Modules\BookingModule\Entities\BookingScheduleHistory;
use Modules\BookingModule\Entities\BookingStatusHistory;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\ServiceManagement\Entities\Service;

class BookingController extends Controller
{

    private Booking $booking;
    private BookingStatusHistory $booking_status_history;
    private BookingDetail $booking_detail;

    use BookingTrait;

    public function __construct(Booking $booking, BookingStatusHistory $booking_status_history, BookingDetail $booking_detail)
    {
        $this->booking = $booking;
        $this->booking_status_history = $booking_status_history;
        $this->booking_detail = $booking_detail;
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
            'booking_status' => 'required|in:all,' . implode(',', array_column(BOOKING_STATUSES, 'key')),
            'booking_otp' => ((business_config('booking_otp', 'booking_setup'))->live_values == 1 && $request->booking_status == 'completed') ? 'required' : 'nullable',
            'evidence_photos' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->where('id', $booking_id)->where(['serviceman_id' => $request->user()->serviceman->id])->first();
        $booking_status = $request->booking_status;

        if (isset($booking)) {

            //evidence photo & OTP
            $evidence_photos = [];
            if ($booking_status == 'completed' && (business_config('booking_otp', 'booking_setup'))?->live_values == 1) {
                if ($booking->booking_otp != $request['booking_otp']) {
                    return response()->json(response_formatter(OTP_VERIFICATION_FAIL_403), 200);
                }

                if ($request->has('evidence_photos')) {
                    foreach ($request->evidence_photos as $image) {
                        $evidence_photos[] = file_uploader('booking/evidence/', 'png', $image);
                    }
                }
            }


            $booking->booking_status = $request['booking_status'];
            $booking->evidence_photos = $evidence_photos;

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


    /**
     * Show the specified resource.
     * @param Request $request
     * @param string $booking_id
     * @return JsonResponse
     */
    public function payment_status_update(Request $request, string $booking_id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'payment_status' => 'required|in:paid,unpaid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->where('id', $booking_id)->where(['serviceman_id' => $request->user()->serviceman->id])->first();
        if (isset($booking)) {
            $booking->is_paid = $request['payment_status'] == 'paid' ? 1 : 0;
            $booking->save();
            return response()->json(response_formatter(DEFAULT_200, $booking), 200);
        }

        return response()->json(response_formatter(DEFAULT_204), 200);
    }


    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function booking_details(Request $request, string $id): JsonResponse
    {
        $booking = $this->booking->with([
            'detail.service', 'schedule_histories.user', 'status_histories.user', 'service_address', 'customer', 'provider', 'zone', 'serviceman.user', 'booking_partial_payments'
        ])->where(function ($query) use ($request) {
            return $query->where('serviceman_id', $request->user()->serviceman->id)->orWhereNull('provider_id');
        })->where(['id' => $id])->first();

        if (isset($booking)) {
            return response()->json(response_formatter(DEFAULT_200, [
                'booking' => $booking,
                'provider_serviceman_can_cancel_booking' => (int)provider_config('provider_serviceman_can_cancel_booking', 'serviceman_config', $booking->provider_id)?->live_values,
                'provider_serviceman_can_edit_booking' => (int)provider_config('provider_serviceman_can_edit_booking', 'serviceman_config', $booking->provider_id)?->live_values,
            ]), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function booking_list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'booking_status' => 'required|in:all,' . implode(',', array_column(BOOKING_STATUSES, 'key')),
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bookings = $this->booking->with(['customer'])->where(['serviceman_id' => auth('api')->user()->serviceman->id])->latest()
            ->when($request['booking_status'] != 'all', function ($query) use ($request) {
                $query->where(['booking_status' => $request['booking_status']]);
            })
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $bookings), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function notification_send(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking
            ->with(['customer'])
            ->where('id', $request['booking_id'])
            ->where(function ($query) use ($request) {
                return $query->where('serviceman_id', $request->user()->serviceman->id);
            })
            ->first();

        if (!isset($booking)) {
            return response()->json(response_formatter(DEFAULT_404), 404);
        }

        $fcm_token = $booking?->customer?->fcm_token;
        $title = translate('Your booking verification OTP is') . ' ' . $booking->booking_otp;

        if ($fcm_token) {
            device_notification($fcm_token, $title, null, null, $booking->id, 'booking', null, $booking?->customer?->id);
            return response()->json(response_formatter(NOTIFICATION_SEND_SUCCESSFULLY_200), 200);

        } else {
            return response()->json(response_formatter(NOTIFICATION_SEND_FAILED_200), 200);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_service_info(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zone_id' => 'required|uuid',
            'service_info' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = [];
        foreach (json_decode($request['service_info'], true) as $item) {
            $service = Service::active()
                ->where('id', $item['service_id'])
                ->with(['category.category_discount', 'category.campaign_discount', 'service_discount'])
                ->with(['variations' => fn($query) => $query->where('variant_key', $item['variant_key'])->where('zone_id', $request['zone_id'])])
                ->first();

            if (!isset($service)) return response()->json(response_formatter(DEFAULT_404, $data), 404);

            //calculation
            $quantity = $item['quantity'];
            $variation_price = $service?->variations[0]?->price;

            $basic_discount = basic_discount_calculation($service, $variation_price * $quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation_price * $quantity);
            $subtotal = round($variation_price * $quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;

            $tax = round((($variation_price * $quantity - $applicable_discount) * $service['tax']) / 100, 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

            $data[] = collect([
                'service_id' => $service->id,
                'service_name' => $service->name,
                'variant_key' => $service?->variations[0]?->variant_key,
                'quantity' => $item['quantity'],
                'service_cost' => $variation_price,
                'total_discount_amount' => $basic_discount + $campaign_discount,
                'coupon_code' => null,
                'tax_amount' => round($tax, 2),
                'total_cost' => round($subtotal - $basic_discount - $campaign_discount + $tax, 2),
                'zone_id' => $request['zone_id']
            ]);
        }

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_booking(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
            'service_info' => 'required',
            'payment_status' => 'nullable|in:0,1',
            'serviceman_id' => 'nullable',
            'booking_status' => 'nullable|in:' . implode(',', array_column(BOOKING_STATUSES, 'key')),
            'service_schedule' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking
            ->with('detail')
            ->where('id', $request['booking_id'])
            ->where('serviceman_id', $request->user()->serviceman->id)
            ->first();

        //update booking
        if (!is_null($request['payment_status'])) $booking->is_paid = $request['payment_status'];
        if (!is_null($request['serviceman_id'])) $booking->serviceman_id = $request['serviceman_id'];
        if (!is_null($request['booking_status'])) $booking->booking_status = $request['booking_status'];
        if (!is_null($request['service_schedule'])) $booking->service_schedule = $request['service_schedule'];
        $booking->save();

        $edit_access = (boolean)provider_config('provider_serviceman_can_edit_booking', 'serviceman_config', $booking->provider_id)?->live_values;
        $request['service_info'] = collect(json_decode($request['service_info'], true));
        $service_info_validated = $request['service_info']?->first()['quantity']??null;

        if ($edit_access && $service_info_validated) {
            //service format for add service
            $existing_services = $this->booking_detail->where('booking_id', $request['booking_id'])->get();
            foreach ($existing_services as $item) {
                if(!$request['service_info']->where('service_id', $item->service_id)->where('variant_key', $item->variant_key)->first()) {
                    $request['service_info']->push([
                        'service_id' => $item->service_id,
                        'variant_key' => $item->variant_key,
                        'quantity' => 0,
                    ]);
                }
            }


            foreach ($request['service_info'] as $key=>$item) {
                $existing_service = $this->booking_detail
                    ->where('booking_id', $request['booking_id'])
                    ->where('service_id', $item['service_id'])
                    ->where('variant_key', $item['variant_key'])
                    ->first();

                if (!$existing_service) {
                    //add new service
                    $request['service_id'] = $item['service_id'];
                    $request['variant_key'] = $item['variant_key'];
                    $request['quantity'] = $item['quantity'];
                    $this->add_new_booking_service($request);

                } else if ($existing_service && $item['quantity'] == 0) {
                    //remove [existing service]
                    $request['service_id'] = $item['service_id'];
                    $request['variant_key'] = $item['variant_key'];
                    $request['quantity'] = $item['quantity'];

                    $this->remove_service_from_booking($request);

                } else if($existing_service && $existing_service->quantity < $item['quantity']) {
                    //add quantity [to existing service]
                    $request['service_id'] = $item['service_id'];
                    $request['variant_key'] = $item['variant_key'];
                    $request['old_quantity'] = $existing_service->quantity;
                    $request['new_quantity'] = (int)$item['quantity'];
                    $this->increase_service_quantity_from_booking($request);

                } else if ($existing_service && $existing_service->quantity > $item['quantity']) {
                    //remove quantity [from existing service]
                    $request['service_id'] = $item['service_id'];
                    $request['variant_key'] = $item['variant_key'];
                    $request['old_quantity'] = $existing_service->quantity;
                    $request['new_quantity'] = (int)$item['quantity'];

                    $this->decrease_service_quantity_from_booking($request);

                }
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_service(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|uuid',
            'service_id' => 'required|uuid',
            'variant_key' => 'required',
            'zone_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking = $this->booking->with('detail')
            ->where('id', $request['booking_id'])
            ->where(fn ($query) => $query->where('serviceman_id', $request->user()->serviceman->id)->orWhereNull('serviceman_id'))
            ->first();

        //if only one service
        if ($booking?->detail->count() < 2) {
            return response()->json(response_formatter(DEFAULT_400), 400);
        }

        //remove service
        $this->remove_service_from_booking($request);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
