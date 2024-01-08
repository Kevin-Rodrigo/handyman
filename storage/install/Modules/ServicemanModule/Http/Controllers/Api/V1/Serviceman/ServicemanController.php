<?php

namespace Modules\ServicemanModule\Http\Controllers\Api\V1\Serviceman;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Entities\BookingScheduleHistory;
use Modules\BookingModule\Entities\BookingStatusHistory;
use Modules\PromotionManagement\Entities\PushNotification;
use Modules\SMSModule\Lib\SMS_gateway;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;
use Modules\PaymentModule\Traits\SmsGateway;

class ServicemanController extends Controller
{
    private Booking $booking;
    private Serviceman $serviceman;
    private User $employee;
    private PushNotification $push_notification;

    public function __construct(Booking $booking, Serviceman $serviceman, User $employee, PushNotification $push_notification)
    {
        $this->booking = $booking;
        $this->serviceman = $serviceman;
        $this->employee = $employee;
        $this->push_notification = $push_notification;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function dashboard(Request $request): JsonResponse
    {
        $request['sections'] = explode(',', $request['sections']);

        $validator = Validator::make($request->all(), [
            'sections' => 'required|array',
            'sections.*' => 'in:top_cards,recent_bookings,booking_stats',
            'year' => 'integer|min:2000|max:' . (date('Y') + 1),
            'month' => 'integer|min:1|max:12',
            'stats_type' => 'in:full_year,full_month'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data = [];
        if (in_array('top_cards', $request['sections'])) {
            $booking_overview = DB::table('bookings')->where('serviceman_id', $request->user()->serviceman->id)
                ->select('booking_status', DB::raw('count(*) as total'))
                ->groupBy('booking_status')
                ->get();

            $data[] = ['top_cards' => [
                'total_bookings' => $booking_overview->sum('total') ?? 0,
                'ongoing_bookings' => $booking_overview->where('booking_status', 'ongoing')->first()->total ?? 0,
                'completed_bookings' => $booking_overview->where('booking_status', 'completed')->first()->total ?? 0,
                'canceled_bookings' => $booking_overview->where('booking_status', 'canceled')->first()->total ?? 0
            ]];
        }

        if (in_array('booking_stats', $request['sections'])) {
            $all_bookings = $this->booking->where(['serviceman_id' => $request->user()->serviceman->id])
                ->when($request->has('stats_type') && $request['stats_type'] == 'full_year', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->select(
                        DB::raw('count(*) as total'),
                        DB::raw('YEAR(created_at) year, MONTH(created_at) month')
                    )->groupby('year', 'month');
                })->when($request->has('stats_type') && $request['stats_type'] == 'full_month', function ($query) use ($request) {
                    return $query->whereYear('created_at', '=', $request['year'])->whereMonth('created_at', '=', $request['month'])->select(
                        DB::raw('count(*) as total'),
                        DB::raw('YEAR(created_at) year, MONTH(created_at) month, DAY(created_at) day')
                    )->groupby('year', 'month', 'day');
                })->get()->toArray();

            $data[] = ['booking_stats' => $all_bookings];
        }

        if (in_array('recent_bookings', $request['sections'])) {
            $bookings = $this->booking->with(['detail.service' => function ($query) {
                $query->select('id', 'name', 'thumbnail');
            }])->where(['serviceman_id' => $request->user()->serviceman->id])->take(5)->latest()->get();
            $data[] = ['bookings' => $bookings];
        }

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }

    public function change_password(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'password' => 'required|min:8|max:50',
            'confirm_password' => 'required|same:password'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->employee->where('id', $request->user()->id)->update(['password' => bcrypt($request->password)]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    public function profile_info(Request $request): JsonResponse
    {
        return response()->json(response_formatter(DEFAULT_UPDATE_200, $request->user()), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $serviceman = $this->serviceman->where(['user_id' => auth('api')->user()->id])->with(['user', 'provider:id,zone_id'])
            ->withCount(['bookings', 'bookings as completed_bookings_count' => function ($query) {
                $query->where('booking_status', 'completed');
            }])->first();

        if ($request->user()->user_type == PROVIDER_USER_TYPES[2]) {
            return response()->json(response_formatter(DEFAULT_200, $serviceman), 200);
        }

        return response()->json(response_formatter(DEFAULT_403), 401);
    }

    /**
     * Modify provider information
     * @param Request $request
     * @return JsonResponse
     */
    public function update_profile(Request $request): JsonResponse
    {
        $employee = $this->employee::find($request->user()->id);
        if (!isset($employee)) {
            return response()->json(response_formatter(DEFAULT_204), 204);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => '',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $employee->first_name = $request->first_name;
        $employee->last_name = $request->last_name;
        $employee->email = $request->email;
        if ($request->has('profile_image')) {
            $employee->profile_image = file_uploader('serviceman/profile/', 'png', $request->file('profile_image'), $employee->profile_image);;
        }
        if ($request->has('password')) {
            $employee->password = bcrypt($request->password);
        }
        $employee->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function push_notifications(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $push_notification = $this->push_notification->ofStatus(1)->whereJsonContains('to_users', PROVIDER_USER_TYPES[2])
            ->whereJsonContains('zone_ids', $request->user()->serviceman->provider->zone_id)
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $push_notification), 200);
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
        $customer = $this->employee->where('user_type', PROVIDER_USER_TYPES[2])
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
                if($published_status == 1){
                    $response = SmsGateway::send($customer->phone, $token);
                }else{
                    SMS_gateway::send($customer->phone, $token);
                }

            } elseif($method == 'email') {
                //mail will be sent
                try {
                    Mail::to($customer['email'])->send(new \App\Mail\PasswordResetMail($token));
                } catch (\Exception $exception) {}
            }

            return response()->json(response_formatter(DEFAULT_SENT_OTP_200), 200);
        }
        return response()->json(response_formatter(DEFAULT_404), 200);
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
            $this->employee->where('user_type', PROVIDER_USER_TYPES[2])
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

        $customer = $this->employee::find($request->user()->id);
        $customer->fcm_token = $request->fcm_token;
        $customer->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
