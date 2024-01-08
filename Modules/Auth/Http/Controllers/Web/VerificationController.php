<?php

namespace Modules\Auth\Http\Controllers\Web;

use Brian2694\Toastr\Facades\Toastr;
use Carbon\CarbonInterval;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\SMSModule\Lib\SMS_gateway;
use Modules\UserManagement\Emails\OTPMail;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserVerification;
use Modules\PaymentModule\Traits\SmsGateway;

class VerificationController extends Controller
{
    public function __construct(
        private User $user,
        private UserVerification $user_verification
    )
    {
    }

    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index(): Renderable
    {
        return view('auth::verification.send-otp');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse|Renderable
     * @throws ValidationException
     */
    public function send_otp(Request $request): RedirectResponse|Renderable
    {
        Validator::make($request->all(), [
            'identity' => 'required|max:255',
            'identity_type' => 'required|in:phone,email'
        ])->validate();

        //provider check
        $user = $this->user->where($request['identity_type'], $request['identity'])->whereIn('user_type', PROVIDER_USER_TYPES)->first();
        if(!isset($user)) {
            Toastr::error(DEFAULT_404['message']);
            return redirect(route('provider.auth.verification.index'));
        }

        //resend time check
        $user_verification = $this->user_verification->where('identity', $request['identity'])->first();
        $otp_resend_time = business_config('otp_resend_time', 'otp_login_setup')?->live_values;


        if (isset($user_verification) && Carbon::parse($user_verification->updated_at)->DiffInSeconds() < $otp_resend_time) {
            $time = $otp_resend_time - Carbon::parse($user_verification->updated_at)->DiffInSeconds();
            Toastr::error(translate('Please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
            return redirect(route('provider.auth.verification.index'));
        }

        $otp = env('APP_ENV') != 'live' ? '1234' : rand(1000, 9999);
        $this->user_verification->updateOrCreate([
            'identity' => $request['identity'],
            'identity_type' => $request['identity_type']
        ], [
            'identity' => $request['identity'],
            'identity_type' => $request['identity_type'],
            'user_id' => null,
            'otp' => $otp,
            'expires_at' => now()->addMinute(3),
        ]);

        //send otp
        if ($request['identity_type'] == 'phone') {
            //for payment and sms gateway addon
            $published_status = 0;
            $payment_published_status = config('get_payment_publish_status');
            if (isset($payment_published_status[0]['is_published'])) {
                $published_status = $payment_published_status[0]['is_published'];
            }

            if($published_status == 1){
                $response = SmsGateway::send($request['identity'], $otp);
            }else{
                $response = SMS_gateway::send($request['identity'], $otp);
            }

        } else if ($request['identity_type'] == 'email') {
            try {
                Mail::to($request['identity'])->send(new OTPMail($otp));
                $response = 'success';
            } catch (\Exception $exception) {
                $response = 'error';
            }
        } else {
            $response = 'error';
        }

        if ($response == 'success') {
            Session::put('identity', $request['identity']);
            Session::put('identity_type', $request['identity_type']);

            Toastr::success(DEFAULT_SENT_OTP_200['message']);
            return view('auth::verification.verify-otp');

        } else {
            Toastr::error(DEFAULT_SENT_OTP_FAILED_200['message']);
            return redirect(route('provider.auth.verification.index'));
        }
    }

    public function verify_otp(Request $request): JsonResponse|Renderable|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'identity' => 'required',
            'identity_type' => 'required',
            'otp' => 'required|max:4'
        ]);

        if ($validator->fails()) {
            $error = error_processor($validator);
            $message = $error[0]['message'] ?? DEFAULT_400['message'];
            Toastr::error($message);
            return redirect(route('provider.auth.verification.index'));
        }

        $max_otp_hit = business_config('maximum_otp_hit', 'otp_login_setup')->test_values ?? 5;
        $max_otp_hit_time = business_config('otp_resend_time', 'otp_login_setup')->test_values ?? 60;// seconds
        $temp_block_time = business_config('temporary_otp_block_time', 'otp_login_setup')->test_values ?? 600; // seconds

        $verify = $this->user_verification->where(['identity' => $request['identity'], 'otp' => $request['otp']])->first();

        if (isset($verify)) {
            if (isset($verify->temp_block_time) && Carbon::parse($verify->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                $time = $temp_block_time - Carbon::parse($verify->temp_block_time)->DiffInSeconds();
                Toastr::success(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                return redirect(route('provider.auth.verification.index'));
            }

            if ($request['identity_type'] == 'email') {
                $user = $this->user->where('email', $request['identity'])->first();
                $user->is_email_verified = 1;
                $user->save();

            } else if ($request['identity_type'] == 'phone') {
                $user = $this->user->where('phone', $request['identity'])->first();
                $user->is_phone_verified = 1;
                $user->save();
            }
            $this->user_verification->where(['identity' => $request['identity'], 'otp' => $request['otp']])->delete();

            Toastr::success(OTP_VERIFICATION_SUCCESS_200['message']);
            return redirect(route('provider.auth.login'));

        } else {
            $verification_data = $this->user_verification->where('identity', $request['identity'])->first();

            if (isset($verification_data)) {
                if (isset($verification_data->temp_block_time) && Carbon::parse($verification_data->temp_block_time)->DiffInSeconds() <= $temp_block_time) {
                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
                    Toastr::error(translate('please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                    return redirect(route('provider.auth.verification.index'));
                }

                if ($verification_data->is_temp_blocked == 1 && Carbon::parse($verification_data->updated_at)->DiffInSeconds() >= $max_otp_hit_time) {

                    $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
                    if (!isset($user_verify)) {
                        $user_verify = $this->user_verification;
                    }
                    $user_verify->hit_count = 0;
                    $user_verify->is_temp_blocked = 0;
                    $user_verify->temp_block_time = null;
                    $user_verify->save();
                }

                if ($verification_data->hit_count >= $max_otp_hit && Carbon::parse($verification_data->updated_at)->DiffInSeconds() < $max_otp_hit_time && $verification_data->is_temp_blocked == 0) {

                    $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
                    if (!isset($user_verify)) {
                        $user_verify = $this->user_verification;
                    }
                    $user_verify->is_temp_blocked = 1;
                    $user_verify->temp_block_time = now();
                    $user_verify->save();

                    $time = $temp_block_time - Carbon::parse($verification_data->temp_block_time)->DiffInSeconds();
                    Toastr::error(translate('Too_many_attempts. please_try_again_after_') . CarbonInterval::seconds($time)->cascade()->forHumans());
                    return redirect(route('provider.auth.verification.index'));
                }
            }

            $user_verify = $this->user_verification->where(['identity' => $request['identity']])->first();
            if (!isset($user_verify)) {
                $user_verify = $this->user_verification;
            }
            $user_verify->hit_count += 1;
            $user_verify->temp_block_time = null;
            $user_verify->save();
        }

        Toastr::error(OTP_VERIFICATION_FAIL_403['message']);
        return redirect(route('provider.auth.verification.index'));
    }
}
