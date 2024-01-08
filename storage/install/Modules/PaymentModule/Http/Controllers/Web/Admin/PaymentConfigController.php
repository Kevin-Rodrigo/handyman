<?php

namespace Modules\PaymentModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\PaymentModule\Entities\Setting;

class PaymentConfigController extends Controller
{
    private BusinessSettings $business_setting;
    private Setting $addon_settings;

    public function __construct(BusinessSettings $business_setting, Setting $addon_settings)
    {
        $this->business_setting = $business_setting;
        $this->addon_settings = $addon_settings;
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function payment_config_get(Request $request): View|Factory|Application
    {
        Validator::make($request->all(), [
            'type' => 'in:digital_payment'
        ])->validate();

        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        $routes = config('addon_admin_routes');
        $desiredName = 'payment_setup';
        $payment_url = '';

        foreach ($routes as $routeArray) {
            foreach ($routeArray as $route) {
                if ($route['name'] === $desiredName) {
                    $payment_url = $route['url'];
                    break 2;
                }
            }
        }

        $data_values = $this->addon_settings
            ->whereIn('settings_type', ['payment_config'])
            ->whereIn('key_name', array_merge(array_column(PAYMENT_METHODS, 'key'), ['ssl_commerz']))
            ->get();

        $type = 'digital_payment';
        $search = $request['search'] ?? '';

        return view('paymentmodule::admin.payment-gateway-config', compact('data_values', 'published_status', 'payment_url', 'type'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function payment_config_set(Request $request): RedirectResponse
    {
        $validation = [
            'gateway' => 'required|in:' . implode(',', array_column(DIGITAL_PAYMENT_METHODS, 'key')),
            'mode' => 'required|in:live,test'
        ];
        $additional_data = [];

        if ($request['gateway'] == 'ssl_commerz') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'store_id' => 'required',
                'store_password' => 'required'
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
        } elseif ($request['gateway'] == 'paystack') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'public_key' => 'required',
                'secret_key' => 'required',
                'merchant_email' => 'required'
            ];
        } elseif ($request['gateway'] == 'flutterwave') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'secret_key' => 'required',
                'public_key' => 'required',
                'hash' => 'required'
            ];
        } elseif ($request['gateway'] == 'paytm') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'merchant_key' => 'required',
                'merchant_id' => 'required',
                'merchant_website_link' => 'required'
            ];
        } elseif ($request['gateway'] == 'cash_after_service') {
            $additional_data = [
                'status' => 'required|in:1,0'
            ];
        } elseif ($request['gateway'] == 'digital_payment') {
            $additional_data = [
                'status' => 'required|in:1,0'
            ];
        }
        $validation = $request->validate(array_merge($validation, $additional_data));
        $addon_settings = $this->addon_settings->where('key_name', $request['gateway'])->where('settings_type', 'payment_config')->first();
        $additional_data_image = $addon_settings['additional_data'] != null ? json_decode($addon_settings['additional_data']) : null;

        if ($request->has('gateway_image')) {
            $gateway_image = file_uploader('payment_modules/gateway_image/', 'png', $request['gateway_image'], $additional_data_image != null ? $additional_data_image->gateway_image : '');
        } else {
            $gateway_image = $additional_data_image != null ? $additional_data_image->gateway_image : '';
        }

        $payment_additional_data = [
            'gateway_title' => $request['gateway_title'],
            'gateway_image' => $gateway_image,
        ];

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));


        $this->addon_settings->updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'payment_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validation,
            'test_values' => $validation,
            'settings_type' => 'payment_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
            'additional_data' => json_encode($payment_additional_data),
        ]);

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }
}
