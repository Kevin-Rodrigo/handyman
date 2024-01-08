<?php

namespace Modules\Auth\Http\Controllers\Api\V1;

use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Modules\CartModule\Entities\Cart;
use Modules\CustomerModule\Traits\CustomerTrait;
use Modules\UserManagement\Entities\User;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class LoginController extends Controller
{
    private User $user;
    use CustomerTrait;
    private array $validation_array = [
        'email_or_phone' => 'required',
        'password' => 'required',
    ];

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function admin_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->validation_array);
        if ($validator->fails()) return response()->json(response_formatter(AUTH_LOGIN_403, null, error_processor($validator)), 403);

        $user = $this->user->where(['phone' => $request['email_or_phone']])
            ->orWhere('email', $request['email_or_phone'])
            ->ofType(ADMIN_USER_TYPES)->first();

        if (isset($user) && Hash::check($request['password'], $user['password'])) {
            if ($user->is_active && $user->roles->count() > 0 && $user->roles[0]->is_active || $user->user_type == 'super-admin') {
                return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, ADMIN_PANEL_ACCESS)), 200);
            }
            return response()->json(response_formatter(ACCOUNT_DISABLED), 401);
        }
        return response()->json(response_formatter(AUTH_LOGIN_401), 401);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function provider_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), $this->validation_array);
        if ($validator->fails()) return response()->json(response_formatter(AUTH_LOGIN_403, null, error_processor($validator)), 403);

        $user = $this->user->where(['phone' => $request['email_or_phone']])
            ->orWhere('email', $request['email_or_phone'])
            ->ofType(PROVIDER_USER_TYPES)->first();

        //not found
        if (!isset($user)) {
            return response()->json(response_formatter(AUTH_LOGIN_404), 404);
        }

        $temp_block_time = business_config('temporary_login_block_time', 'otp_login_setup')?->live_values ?? 600; // seconds

        //if temporarily blocked
        if ($user->is_temp_blocked) {
            //if 'temporary block period' has not expired
            if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                return response()->json(response_formatter([
                    "response_code" => "auth_login_401",
                    "message" => translate('Your account is temporarily blocked. Please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                ]), 401);
            }

            //reset
            $user->login_hit_count = 0;
            $user->is_temp_blocked = 0;
            $user->temp_block_time = null;
            $user->save();
        }

        //phone verification
        $phone_verification = business_config('phone_verification', 'service_setup')?->live_values ?? 0;
        if ($phone_verification && !$user->is_phone_verified) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(UNVERIFIED_PHONE), 401);
        }

        //email verification
        $email_verification = business_config('email_verification', 'service_setup')?->live_values ?? 0;
        if ($email_verification && !$user->is_email_verified) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(UNVERIFIED_EMAIL), 401);
        }

        //credentials mismatch
        if (!Hash::check($request['password'], $user['password'])) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(AUTH_LOGIN_401), 401);
        }

        //not active
        if (!$user->is_active) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(ACCOUNT_DISABLED), 401);
        }

        //req within blocking
        if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
            return response()->json(response_formatter([
                "response_code" => "auth_login_401",
                "message" => translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans()
            ]), 401);
        }

        //login success
        return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, PROVIDER_PANEL_ACCESS)), 200);
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function customer_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $validator = Validator::make($request->all(), $this->validation_array);
        if ($validator->fails()) return response()->json(response_formatter(AUTH_LOGIN_403, null, error_processor($validator)), 403);

        $user = $this->user
            ->where(['phone' => $request['email_or_phone']])
            ->orWhere('email', $request['email_or_phone'])
            ->ofType(CUSTOMER_USER_TYPES)
            ->first();

        //not found
        if (!isset($user)) {
            return response()->json(response_formatter(AUTH_LOGIN_404), 404);
        }

        $temp_block_time = business_config('temporary_login_block_time', 'otp_login_setup')?->live_values ?? 600; // seconds

        //if temporarily blocked
        if ($user->is_temp_blocked) {
            //if 'temporary block period' has not expired
            if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                return response()->json(response_formatter([
                    "response_code" => "auth_login_401",
                    "message" => translate('Your account is temporarily blocked. Please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                ]), 401);
            }

            //reset
            $user->login_hit_count = 0;
            $user->is_temp_blocked = 0;
            $user->temp_block_time = null;
            $user->save();
        }

        //credentials mismatch
        if (!Hash::check($request['password'], $user['password'])) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(AUTH_LOGIN_401), 401);
        }

        //phone verification
        $phone_verification = business_config('phone_verification', 'service_setup')?->live_values ?? 0;
        if ($phone_verification && !$user->is_phone_verified) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(UNVERIFIED_PHONE), 401);
        }

        //email verification
        $email_verification = business_config('email_verification', 'service_setup')?->live_values ?? 0;
        if ($email_verification && !$user->is_email_verified) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(UNVERIFIED_EMAIL), 401);
        }

        //not active
        if (!$user->is_active) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(ACCOUNT_DISABLED), 401);
        }

        //req within blocking
        if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
            return response()->json(response_formatter([
                "response_code" => "auth_login_401",
                "message" => translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans()
            ]), 401);
        }

        //login success
        $this->update_address_and_cart_user($user->id, $request['guest_id']);
        return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, CUSTOMER_PANEL_ACCESS)), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function customer_logout(Request $request): JsonResponse
    {
        if (!auth()->user()) {
            return response()->json(response_formatter(ACCESS_DENIED), 200);
        }

        $request->user()->token()->revoke();
        return response()->json(response_formatter(AUTH_LOGOUT_200), 200);
    }

    public function update_user_hit_count($user)
    {
        $max_login_hit = business_config('maximum_login_hit', 'otp_login_setup')?->live_values ?? 5;

        $user->login_hit_count += 1;
        if ($user->login_hit_count >= $max_login_hit) {
            $user->is_temp_blocked = 1;
            $user->temp_block_time = now();
        }
        $user->save();
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function serviceman_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) return response()->json(response_formatter(AUTH_LOGIN_403, null, error_processor($validator)), 403);

        $user = $this->user
            ->where(['phone' => $request['phone']])
            ->ofType([SERVICEMAN_USER_TYPES])
            ->first();

        //not found
        if (!isset($user)) {
            return response()->json(response_formatter(AUTH_LOGIN_404), 404);
        }

        $temp_block_time = business_config('temporary_login_block_time', 'otp_login_setup')?->live_values ?? 600; // seconds

        //if temporarily blocked
        if ($user->is_temp_blocked) {
            //if 'temporary block period' has not expired
            if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
                $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
                return response()->json(response_formatter([
                    "response_code" => "auth_login_401",
                    "message" => translate('Your account is temporarily blocked. Please_try_again_after_'). CarbonInterval::seconds($time)->cascade()->forHumans(),
                ]), 401);
            }

            //reset
            $user->login_hit_count = 0;
            $user->is_temp_blocked = 0;
            $user->temp_block_time = null;
            $user->save();
        }

        //credentials mismatch
        if (!Hash::check($request['password'], $user['password'])) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(AUTH_LOGIN_401), 401);
        }

        //not active
        if (!$user->is_active) {
            self::update_user_hit_count($user);
            return response()->json(response_formatter(ACCOUNT_DISABLED), 401);
        }

        //req within blocking
        if(isset($user->temp_block_time) && Carbon::parse($user->temp_block_time)->DiffInSeconds() <= $temp_block_time){
            $time = $temp_block_time - Carbon::parse($user->temp_block_time)->DiffInSeconds();
            return response()->json(response_formatter([
                "response_code" => "auth_login_401",
                "message" => translate('Try_again_after') . ' ' . CarbonInterval::seconds($time)->cascade()->forHumans()
            ]), 401);
        }

        //login success
        return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, SERVICEMAN_APP_ACCESS)), 200);
    }


    public function social_customer_login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'unique_id' => 'required',
            'email' => 'required_if:medium,google,facebook',
            'medium' => 'required|in:google,facebook,apple',
            'guest_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $client = new Client();
        $token = $request['token'];
        $email = $request['email'];
        $unique_id = $request['unique_id'];

        try {
            if ($request['medium'] == 'google') {
                $res = $client->request('GET', 'https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=' . $token);
                $data = json_decode($res->getBody()->getContents(), true);
            } elseif ($request['medium'] == 'facebook') {
                $res = $client->request('GET', 'https://graph.facebook.com/' . $unique_id . '?access_token=' . $token . '&&fields=name,email');
                $data = json_decode($res->getBody()->getContents(), true);
            }elseif ($request['medium'] == 'apple') {
                $apple_login = (business_config('apple_login', 'third_party'))->live_values;
                $teamId = $apple_login['team_id'];
                $keyId = $apple_login['key_id'];
                $sub = $apple_login['client_id'];
                $aud = 'https://appleid.apple.com';
                $iat = strtotime('now');
                $exp = strtotime('+60days');
                $keyContent = file_get_contents('storage/app/public/apple-login/'.$apple_login['service_file']);
                $token = JWT::encode([
                    'iss' => $teamId,
                    'iat' => $iat,
                    'exp' => $exp,
                    'aud' => $aud,
                    'sub' => $sub,
                ], $keyContent, 'ES256', $keyId);

                $redirect_uri = $apple_login['redirect_url']??'www.example.com/apple-callback';

                $res = Http::asForm()->post('https://appleid.apple.com/auth/token', [
                    'grant_type' => 'authorization_code',
                    'code' => $unique_id,
                    'redirect_uri' => $redirect_uri,
                    'client_id' => $sub,
                    'client_secret' => $token,
                ]);

                $claims = explode('.', $res['id_token'])[1];
                $data = json_decode(base64_decode($claims),true);
            }
        } catch (\Exception $exception) {
            return response()->json(response_formatter(DEFAULT_401), 200);
        }

        if(!isset($claims)){

            if (strcmp($email, $data['email']) != 0 || (!isset($data['id']) && !isset($data['kid']))) {
                return response()->json(['error' => translate('messages.email_does_not_match')],403);
            }
        }

        $user = $this->user->where('email', $data['email'])
        ->ofType(CUSTOMER_USER_TYPES)
        ->first();

        if ($request['medium'] == 'apple') {

            if (!isset($user)) {
                $user = $this->user;
                $user->first_name = implode('@', explode('@', $data['email'], -1));
                $user->last_name = '';
                $user->email = $data['email'];
                $user->phone = null;
                $user->profile_image = 'def.png';
                $user->date_of_birth = date('y-m-d');
                $user->gender = 'others';
                $user->password = bcrypt($request->ip());
                $user->user_type = 'customer';
                $user->is_active = 1;
                $user->save();
            }

            $this->update_address_and_cart_user($user->id, $request['guest_id']);
            return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, CUSTOMER_PANEL_ACCESS)), 200);
        }

        if ($request['medium'] != 'apple' && strcmp($email, $data['email']) === 0) {
            $user = $this->user->where('email', $request['email'])
                ->ofType(CUSTOMER_USER_TYPES)
                ->first();

            if (!isset($user)) {
                $name = explode(' ', $data['name']);
                if (count($name) > 1) {
                    $fast_name = implode(" ", array_slice($name, 0, -1));
                    $last_name = end($name);
                } else {
                    $fast_name = implode(" ", $name);
                    $last_name = '';
                }

                $user = $this->user;
                $user->first_name = $fast_name;
                $user->last_name = $last_name;
                $user->email = $data['email'];
                $user->phone = null;
                $user->profile_image = 'def.png';
                $user->date_of_birth = date('y-m-d');
                $user->gender = 'others';
                $user->password = bcrypt($request->ip());
                $user->user_type = 'customer';
                $user->is_active = 1;
                $user->save();
            }

            $this->update_address_and_cart_user($user->id, $request['guest_id']);
            return response()->json(response_formatter(AUTH_LOGIN_200, self::authenticate($user, CUSTOMER_PANEL_ACCESS)), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 401);
    }

    /**
     * Show the form for creating a new resource.
     * @return array
     */
    protected function authenticate($user, $access_type)
    {
        return ['token' => $user->createToken($access_type)->accessToken, 'is_active' => $user['is_active']];
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        if ($request->user() !== null) {
            $request->user()->token()->revoke();
        }
        return response()->json(response_formatter(AUTH_LOGOUT_200), 200);
    }
}
