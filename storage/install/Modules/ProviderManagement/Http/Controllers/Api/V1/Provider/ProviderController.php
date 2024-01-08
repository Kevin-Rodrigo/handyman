<?php

namespace Modules\ProviderManagement\Http\Controllers\Api\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\PromotionManagement\Entities\PushNotification;
use Modules\ProviderManagement\Entities\BankDetail;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ProviderManagement\Entities\SubscribedService;
use Modules\ReviewModule\Entities\Review;
use Modules\SMSModule\Lib\SMS_gateway;
use Modules\TransactionModule\Entities\Account;
use Modules\TransactionModule\Entities\Transaction;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\PaymentModule\Traits\SmsGateway;

class ProviderController extends Controller
{
    private $bankDetail, $provider, $account, $user, $push_notification, $serviceman;
    private $google_map;
    private $subscribedService;
    private Booking $booking;
    private Review $review;

    public function __construct(SubscribedService $subscribedService, BankDetail $bankDetail, Provider $provider, Account $account, User $user, PushNotification $pushNotification, Serviceman $serviceman, Booking $booking, Review $review)
    {
        $this->bankDetail = $bankDetail;
        $this->provider = $provider;
        $this->user = $user;
        $this->account = $account;
        $this->push_notification = $pushNotification;
        $this->serviceman = $serviceman;
        $this->subscribedService = $subscribedService;
        $this->google_map = business_config('google_map', 'third_party');
        $this->booking = $booking;
        $this->review = $review;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param Transaction $transaction
     * @param SubscribedService $subscribedService
     * @param Serviceman $serviceman
     * @return JsonResponse
     */
    public function dashboard(Request $request, Transaction $transaction, SubscribedService $subscribedService, Serviceman $serviceman): JsonResponse
    {
        $request['sections'] = explode(',', $request['sections']);

        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*' => 'in:top_cards,earning_stats,booking_stats,recent_bookings,my_subscriptions,serviceman_list',
            'year' => 'integer|min:2000|max:' . (date('Y') + 1),
            'month' => 'integer|min:1|max:12',
            'stats_type' => 'in:full_year,full_month'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = [];

        $max_booking_amount = (business_config('max_booking_amount', 'booking_setup'))->live_values;

        if (in_array('top_cards', $request['sections'])) {
            $account = $this->account->where('user_id', $request->user()->id)->first();
            $data[] = ['top_cards' => [
                'total_earning' => $account['received_balance'] + $account['total_withdrawn'],
                'total_subscribed_services' => $this->subscribedService->where('provider_id', $request->user()->provider->id)
                    ->with(['sub_category'])
                    ->whereHas('category', function ($query) {
                        $query->where('is_active', 1);
                    })->whereHas('sub_category', function ($query) {
                        $query->where('is_active', 1);
                    })
                    ->ofStatus(1)
                    ->count(),
                'total_service_man' => $this->serviceman->where(['provider_id' => $request->user()->provider->id])->count(),
                'total_booking_served' => $request->user()->provider->bookings('completed')->count()
            ]];
        }

        if (in_array('earning_stats', $request['sections'])) {
            $all_transactions = $transaction->where(['to_user_id' => $request->user()->id])->where('credit', '>', 0)
                ->whereIn('to_user_account', ['received_balance', 'total_withdrawn'])
                ->when($request->has('stats_type') && $request['stats_type'] == 'full_year', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month')
                    )->groupby('year', 'month');
                })->when($request->has('stats_type') && $request['stats_type'] == 'full_month', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->whereMonth('created_at', '=', $request['month'])->select(
                        DB::raw('IFNULL(sum(credit),0) as sums'),
                        DB::raw('YEAR(created_at) year, MONTHNAME(created_at) month, DAY(created_at) day')
                    )->groupby('year', 'month', 'day');
                })->get()->toArray();

            $data[] = ['earning_stats' => $all_transactions];
        }

        if (in_array('booking_stats', $request['sections'])) {
            $booking_overview = DB::table('bookings')->where('provider_id', $request->user()->provider->id)
                ->select('booking_status', DB::raw('count(*) as total'))
                ->groupBy('booking_status')
                ->get();
            $total_bookings = $this->booking->where('provider_id', $request->user()->provider->id)->count();
            $data[] = ['booking_stats' => $booking_overview, 'total_bookings' => $total_bookings];
        }

        if (in_array('recent_bookings', $request['sections'])) {
            $subscribed_sub_categories = $this->subscribedService
                ->where(['provider_id' => $request->user()->provider->id])
                ->where(['is_subscribed' => 1])->pluck('sub_category_id')->toArray();

            $recent_bookings = $this->booking->with(['detail.service' => function ($query) {
                $query->select('id', 'name', 'thumbnail');

            }])->where('booking_status', 'pending')
                ->whereIn('sub_category_id', $subscribed_sub_categories)
                ->when($max_booking_amount > 0, function($query) use ($max_booking_amount) {
                    $query->where(function ($query) use ($max_booking_amount) {
                        $query->where('payment_method', 'cash_after_service')
                            ->where(function ($query) use ($max_booking_amount) {
                                $query->where('is_verified', 1)
                                    ->orWhere('total_booking_amount', '<=', $max_booking_amount);
                            })
                            ->orWhere('payment_method', '<>', 'cash_after_service');
                    });
                })
                ->latest()->take(5)->get();
            $data[] = ['recent_bookings' => $recent_bookings];
        }

        if (in_array('my_subscriptions', $request['sections'])) {
            $subscriptions = $subscribedService
                ->whereHas('category', function ($query) {
                    $query->where('is_active', 1);
                })->whereHas('sub_category', function ($query) {
                    $query->where('is_active', 1);
                })
                ->ofStatus(1)
                ->with(['sub_category'])
                ->withCount(['services', 'completed_booking'])
                ->where(['provider_id' => $request->user()->provider->id])->take(5)->get();
            $data[] = ['subscriptions' => $subscriptions];
        }

        if (in_array('serviceman_list', $request['sections'])) {
            $serviceman_list = $this->serviceman->with(['user'])->whereHas('user', function ($query) {
                $query->ofStatus(1);
            })
                ->where(['provider_id' => $request->user()->provider->id])
                ->latest()
                ->take(5)->get();

            $data[] = ['serviceman_list' => $serviceman_list];
        }

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $provider = $this->provider->where(['user_id' => auth('api')->user()->id])->with(['owner', 'zone'])->first();
        if (in_array($request->user()->user_type, PROVIDER_USER_TYPES)) {
            return response()->json(response_formatter(DEFAULT_200, $provider), 200);
        }
        return response()->json(response_formatter(DEFAULT_403), 401);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function get_bank_details(Request $request): JsonResponse
    {
        $bank_details = $this->bankDetail->where('provider_id', $request->user()->provider->id)->first();

        return response()->json(response_formatter(DEFAULT_200, $bank_details), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_bank_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'branch_name' => 'required',
            'acc_no' => 'required',
            'acc_holder_name' => 'required',
            'routing_number' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->bankDetail->updateOrCreate(
            [
                'provider_id' => $request->user()->provider->id,
            ],
            [
                'provider_id' => $request->user()->provider->id,
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'acc_no' => $request->acc_no,
                'acc_holder_name' => $request->acc_holder_name,
                'routing_number' => $request->routing_number
            ],
        );

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }

    /**
     * Modify provider information
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'contact_person_name' => 'required',
            'contact_person_phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            'contact_person_email' => 'required',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',

            'password' => '',
            'confirm_password' => isset($request->password) ? 'required|same:password' : '',

            'company_name' => 'required',
            'company_phone' => 'required|unique:providers,id,' . auth()->user()->provider->id,
            'company_address' => 'required',
            'logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',

            'latitude' => 'required',
            'longitude' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $provider = $this->provider::where('user_id', $request->user()->id)->first();
        $provider->company_name = $request->company_name;
        $provider->company_phone = $request->company_phone;
        if ($request->has('logo')) {
            $provider->logo = file_uploader('provider/logo/', 'png', $request->file('logo'), $provider->logo);
        }
        $provider->company_address = $request->company_address;

        $provider->contact_person_name = $request->contact_person_name;
        $provider->contact_person_phone = $request->contact_person_phone;
        $provider->contact_person_email = $request->contact_person_email;
        $provider->zone_id = $request['zone_ids'][0];
        $provider->coordinates = ['latitude' => $request['latitude'], 'longitude' => $request['longitude']];

        $owner = $this->user->where('id', $request->user()->id)->first();
        if ($request->has('password')) {
            $owner->password = bcrypt($request->password);
        }

        DB::transaction(function () use ($provider, $owner) {
            $owner->save();
            $provider->save();
        });

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function forgot_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_or_email' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        DB::table('password_resets')->where('phone', $request['phone_or_email'])->delete();
        $customer = $this->user->whereIn('user_type', PROVIDER_USER_TYPES)
            ->where(['phone' => $request['phone_or_email']])
            ->first();

        if (isset($customer)) {
            $token = env('APP_ENV') != 'live' ? '1234' : rand(1000, 9999);

            DB::table('password_resets')->insert([
                'phone' => $customer['phone'],
                'email' => $customer['email'],
                'token' => $token,
                'created_at' => now(),
                'expires_at' => now()->addMinutes(3),
            ]);

            $method = business_config('forget_password_verification_method', 'business_information')?->live_values;
            if ($method == 'phone') {
                $published_status = 0;
                $payment_published_status = config('get_payment_publish_status');
                if (isset($payment_published_status[0]['is_published'])) {
                    $published_status = $payment_published_status[0]['is_published'];
                }

                if ($published_status == 1) {
                    $response = SmsGateway::send($customer->phone, $token);
                } else {
                    SMS_gateway::send($customer->phone, $token);
                }

            } elseif ($method == 'email') {
                //mail will be sent
                try {
                    Mail::to($customer['email'])->send(new \App\Mail\PasswordResetMail($token));
                } catch (\Exception $exception) {
                }
            }

        } else {
            return response()->json(response_formatter(DEFAULT_404), 200);
        }

        return response()->json(response_formatter(DEFAULT_SENT_OTP_200), 200);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function otp_verification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_or_email' => 'required',
            'otp' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = DB::table('password_resets')
            ->where('phone', $request['phone_or_email'])
            ->where(['token' => $request['otp']])->first();

        if (isset($data)) {
            return response()->json(response_formatter(DEFAULT_VERIFIED_200), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

    /**
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function reset_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone_or_email' => 'required',
            'otp' => 'required',
            'password' => 'required',
            'confirm_password' => 'required|same:confirm_password'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = DB::table('password_resets')
            ->where('phone', $request['phone_or_email'])
            ->where(['token' => $request['otp']])
            ->where('expires_at', '>', now())
            ->first();

        if (isset($data)) {
            $this->user->whereIn('user_type', PROVIDER_USER_TYPES)
                ->where('phone', $request['phone_or_email'])
                ->update([
                    'password' => bcrypt(str_replace(' ', '', $request['password']))
                ]);
            DB::table('password_resets')
                ->where('phone', $request['phone_or_email'])
                ->where(['token' => $request['otp']])->delete();

        } else {
            return response()->json(response_formatter(DEFAULT_404), 200);
        }

        return response()->json(response_formatter(DEFAULT_PASSWORD_RESET_200), 200);
    }


    /**
     * Modify provider information
     * @param Request $request
     * @return JsonResponse
     */
    public function update_fcm_token(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'fcm_token' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $customer = $this->user::find($request->user()->id);
        $customer->fcm_token = $request->fcm_token;
        $customer->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function notifications(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $push_notification = $this->push_notification->ofStatus(1)->whereJsonContains('to_users', 'provider-admin')
            ->whereJsonContains('zone_ids', $request->user()->provider->zone_id)
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $push_notification), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function subscribed_sub_categories(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $subscribed = $this->subscribedService->where('provider_id', $request->user()->provider->id)
            ->with(['sub_category' => function ($query) {
                return $query->withCount('services')->with(['services']);
            }])->whereHas('category', function ($query) {
                $query->where('is_active', 1);
            })->whereHas('sub_category', function ($query) {
                $query->where('is_active', 1);
            })
            ->ofStatus(1)
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $subscribed), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @param string $service_id
     * @return JsonResponse
     */
    public function review(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review->with(['booking.detail', 'provider', 'customer'])
            ->where('provider_id', $request->user()->provider->id)
            ->ofStatus(1)->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $rating_group_count = DB::table('reviews')->where('provider_id', $request->user()->provider->id)
            ->select('review_rating', DB::raw('count(*) as total'))
            ->groupBy('review_rating')
            ->get();

        $total_rating = 0;
        $rating_count = 0;
        foreach ($rating_group_count as $count) {
            $total_rating += round($count->review_rating * $count->total, 2);
            $rating_count += $count->total;
        }

        $rating_info = [
            'rating_count' => $rating_count,
            'average_rating' => round(divnum($total_rating, $rating_count), 2),
            'rating_group_count' => $rating_group_count,
        ];

        if ($reviews->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, ['reviews' => $reviews, 'rating' => $rating_info]), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

}
