<?php

namespace Modules\SMSModule\Http\Controllers\Web\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\RedirectResponse;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Validator;
use Modules\PaymentModule\Entities\Setting;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Foundation\Application;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class SMSConfigController extends Controller
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
    public function sms_config_get(): View|Factory|Application
    {
        $published_status = 0; // Set a default value
        $payment_published_status = config('get_payment_publish_status');
        if (isset($payment_published_status[0]['is_published'])) {
            $published_status = $payment_published_status[0]['is_published'];
        }

        $routes = config('addon_admin_routes');
        $desiredName = 'sms_setup';
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
        ->whereIn('settings_type', ['sms_config'])
        ->whereIn('key_name', array_column(SMS_GATEWAY, 'key'))
        ->get();
        return view('smsmodule::admin.sms-config', compact('data_values', 'published_status', 'payment_url'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function sms_config_set(Request $request): RedirectResponse
    {
        $validation = [
            'gateway' => 'required|in:releans,twilio,nexmo,2factor,msg91',
            'mode' => 'required|in:live,test'
        ];
        $additional_data = [];
        if ($request['gateway'] == 'releans') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'twilio') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'sid' => 'required',
                'messaging_service_sid' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == 'nexmo') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required',
                'api_secret' => 'required',
                'token' => 'required',
                'from' => 'required',
                'otp_template' => 'required'
            ];
        } elseif ($request['gateway'] == '2factor') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'api_key' => 'required'
            ];
        } elseif ($request['gateway'] == 'msg91') {
            $additional_data = [
                'status' => 'required|in:1,0',
                'template_id' => 'required',
                'auth_key' => 'required',
            ];
        }
        $validation = $request->validate(array_merge($validation, $additional_data));

        $this->addon_settings->updateOrCreate(['key_name' => $request['gateway'], 'settings_type' => 'sms_config'], [
            'key_name' => $request['gateway'],
            'live_values' => $validation,
            'test_values' => $validation,
            'settings_type' => 'sms_config',
            'mode' => $request['mode'],
            'is_active' => $request['status'],
        ]);

        if ($request['status'] == 1) {
            foreach (['releans', 'twilio', 'nexmo', '2factor', 'msg91'] as $gateway) {
                if ($request['gateway'] != $gateway) {
                    $keep = $this->addon_settings->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->first();
                    if (isset($keep)) {
                        $hold = $keep->live_values;
                        $hold['status'] = 0;
                        $this->addon_settings->where(['key_name' => $gateway, 'settings_type' => 'sms_config'])->update([
                            'live_values' => $hold,
                            'test_values' => $hold,
                            'is_active' => 0,
                        ]);
                    }
                }
            }
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }
}
