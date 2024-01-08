<?php

namespace Modules\PaymentModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class PaymentConfigController extends Controller
{
    private BusinessSettings $business_setting;

    public function __construct(BusinessSettings $business_setting)
    {
        $this->business_setting = $business_setting;
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function payment_config_get(): JsonResponse
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['payment_config'])->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function payment_config_set(Request $request): JsonResponse
    {
        $validation = [
            'gateway' => 'required|in:sslcommerz,paypal,stripe,razor_pay,senang_pay,paytabs,paystack,paymob,paytm,flutterwave,liqpay,bkash,mercadopago',
            'mode' => 'required|in:live,test'
        ];
        $additional_data = [];

        if ($request['gateway'] == 'sslcommerz') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'store_id' => 'required',
                'store_password' => 'required'
            ];
        } elseif ($request['gateway'] == 'paypal') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'client_id' => 'required',
                'client_secret' => 'required'
            ];
        } elseif ($request['gateway'] == 'stripe') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'published_key' => 'required',
            ];
        } elseif ($request['gateway'] == 'razor_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required'
            ];
        } elseif ($request['gateway'] == 'senang_pay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required',
                'secret_key' => 'required',
                'merchant_id' => 'required'
            ];
        }elseif ($request['gateway'] == 'paytabs') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'profile_id' => 'required',
                'server_key' => 'required',
                'base_url_by_region' => 'required'
            ];
        }elseif ($request['gateway'] == 'paystack') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required',
                'public_key' => 'required',
                'secret_key' => 'required',
                'merchant_email' => 'required'
            ];
        }elseif ($request['gateway'] == 'paymob') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'callback_url' => 'required',
                'api_key' => 'required',
                'iframe_id' => 'required',
                'integration_id' => 'required',
                'hmac' => 'required'
            ];
        }elseif ($request['gateway'] == 'mercadopago') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'access_token' => 'required',
                'public_key' => 'required'
            ];
        }elseif ($request['gateway'] == 'liqpay') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'private_key' => 'required',
                'public_key' => 'required'
            ];
        }elseif ($request['gateway'] == 'flutterwave') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required',
                'public_key' => 'required',
                'hash' => 'required'
            ];
        }elseif ($request['gateway'] == 'paytm') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required',
                'merchant_id' => 'required',
                'merchant_website_link' => 'required'
            ];
        }elseif ($request['gateway'] == 'bkash') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required',
                'username' => 'required',
                'password' => 'required',
            ];
        }
        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->business_setting->updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'payment_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'settings_type' => 'payment_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
