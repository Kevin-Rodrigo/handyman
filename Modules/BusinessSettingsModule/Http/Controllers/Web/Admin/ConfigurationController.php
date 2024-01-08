<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class ConfigurationController extends Controller
{

    private BusinessSettings $business_setting;

    public function __construct(BusinessSettings $business_setting)
    {
        $this->business_setting = $business_setting;
    }

    /**
     * Display a listing of the resource.
     */
    public function notification_settings_get(): Factory|View|Application
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['notification_settings', 'notification_messages'])->get();
        return view('businesssettingsmodule::admin.notification', compact('data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function notification_settings_set(Request $request): JsonResponse
    {
        $request[$request['key']] = $request['value'];

        $validator = Validator::make($request->all(), [
            'push_notification_booking' => 'in:0,1',
            'email_booking' => 'in:0,1',
            'push_notification_subscription' => 'in:0,1',
            'email_subscription' => 'in:0,1',
            'push_notification_rating_review' => 'in:0,1',
            'email_rating_review' => 'in:0,1',
            'push_notification_tnc_update' => 'in:0,1',
            'email_tnc_update' => 'in:0,1',
            'push_notification_pp_update' => 'in:0,1',
            'email_pp_update' => 'in:0,1'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = ['booking', 'subscription', 'rating_review', 'tnc_update', 'pp_update'];
        foreach ($keys as $key => $value) {
            $request['email_' . $value] = 0;
            if ($request->has('push_notification_' . $value) && $request->has('email_' . $value)) {
                $this->business_setting->updateOrCreate(['key_name' => $value, 'settings_type' => 'notification_settings'], [
                    'key_name' => $value,
                    'live_values' => [
                        'push_notification_' . $value => $request['push_notification_' . $value],
                        'email_' . $value => $request['email_' . $value],
                    ],
                    'test_values' => [
                        'push_notification_' . $value => $request['push_notification_' . $value],
                        'email_' . $value => $request['email_' . $value],
                    ],
                    'settings_type' => 'notification_settings',
                    'mode' => 'live',
                    'is_active' => 1,
                ]);
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function message_settings_set(Request $request): JsonResponse
    {
        $request[$request['id'] . '_status'] = $request['status'];
        $request[$request['id'] . '_message'] = $request['message'];

        $validator = Validator::make($request->all(), [
            'booking_place_message' => 'string',
            'booking_place_status' => 'in:0,1',
            'booking_accepted_message' => 'string',
            'booking_accepted_status' => 'in:0,1',
            'booking_refund_message' => 'string',
            'booking_refund_status' => 'in:0,1',
            'booking_cancel_message' => 'string',
            'booking_cancel_status' => 'in:0,1',
            'booking_service_complete_message' => 'string',
            'booking_service_complete_status' => 'in:0,1',
            'booking_ongoing_message' => 'string',
            'booking_ongoing_status' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $booking_keys = ['booking_place', 'booking_accepted', 'booking_refund', 'booking_cancel', 'booking_service_complete', 'booking_ongoing'];
        foreach ($booking_keys as $key => $value) {
            if ($request->has($value . '_status') && $request->has($value . '_message')) {
                $this->business_setting->updateOrCreate(['key_name' => $value, 'settings_type' => 'notification_messages'], [
                    'key_name' => $value,
                    'live_values' => [
                        $value . '_status' => $request[$value . '_status'],
                        $value . '_message' => $request[$value . '_message'],
                    ],
                    'test_values' => [
                        $value . '_status' => $request[$value . '_status'],
                        $value . '_message' => $request[$value . '_message'],
                    ],
                    'settings_type' => 'notification_messages',
                    'mode' => 'live',
                    'is_active' => $request[$value . '_status'],
                ]);
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function email_config_get(): View|Factory|Application
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['email_config'])->get();
        return view('businesssettingsmodule::admin.email-config', compact('data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function email_config_set(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'mailer_name' => 'required',
            'host' => 'required',
            'driver' => 'required',
            'port' => 'required',
            'user_name' => 'required',
            'email_id' => 'required',
            'encryption' => 'required',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }

        $this->business_setting->updateOrCreate(['key_name' => 'email_config'], [
            'key_name' => 'email_config',
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'settings_type' => 'email_config',
            'mode' => 'live',
            'is_active' => 1,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function third_party_config_get(): View|Factory|Application
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['third_party'])->get();
        return view('businesssettingsmodule::admin.third-party', compact('data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function third_party_config_set(Request $request): JsonResponse
    {
        $validation = [
            'party_name' => 'required|in:google_map,push_notification,recaptcha,apple_login',
            'status' => 'in:0,1'
        ];

        $additional_data = [];
        if ($request['party_name'] == 'google_map') {
            $additional_data = [
                'map_api_key_client' => 'required',
                'map_api_key_server' => 'required'
            ];
        } elseif ($request['party_name'] == 'push_notification') {
            $additional_data = [
                'server_key' => 'required'
            ];
        } elseif ($request['party_name'] == 'recaptcha') {
            $additional_data = [
                'status' => 'required',
                'site_key' => 'required',
                'secret_key' => 'required'
            ];
        } elseif ($request['party_name'] == 'apple_login') {
            $additional_data = [
                'status' => 'required',
                'client_id' => 'required',
                'team_id' => 'required',
                'key_id' => 'required',
                'service_file' => 'nullable',
            ];
        }

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $apple_login = (business_config('apple_login', 'third_party'))->live_values;

        if ($request->hasfile('service_file')) {
            $fileName = file_uploader('apple-login/', 'p8', $request->file('service_file'));
            $liveValues = $validator->validated();
            $liveValues['service_file'] = $fileName;
        } else {
            $liveValues = $validator->validated();
            $liveValues['service_file'] = $apple_login['service_file'];
        }

        $this->business_setting->updateOrCreate(['key_name' => $request['party_name'], 'settings_type' => 'third_party'], [
            'key_name' => $request['party_name'],
            'live_values' => $liveValues,
            'test_values' => $liveValues,
            'settings_type' => 'third_party',
            'mode' => 'live',
            'is_active' => $request->status ?? 0,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @return Application|Factory|View
     */
    public function app_settings_config_get(): View|Factory|Application
    {
        $values = $this->business_setting->whereIn('key_name', ['customer_app_settings'])->first();
        $customer_data_values = isset($values) ? json_decode($values->live_values) : null;

        $values = $this->business_setting->whereIn('key_name', ['provider_app_settings'])->first();
        $provider_data_values = isset($values) ? json_decode($values->live_values) : null;

        $values = $this->business_setting->whereIn('key_name', ['serviceman_app_settings'])->first();
        $serviceman_data_values = isset($values) ? json_decode($values->live_values) : null;


        $social_login_configs = $this->business_setting->where('settings_type', 'social_login')->get();

        return view('businesssettingsmodule::admin.app-settings', compact('customer_data_values', 'provider_data_values', 'serviceman_data_values', 'social_login_configs'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function app_settings_config_set(Request $request): RedirectResponse
    {
        $request->validate([
            'min_version_for_android' => 'required',
            'min_version_for_ios' => 'required',
            'app_type' => 'in:customer,provider,serviceman'
        ]);

        if ($request['app_type'] == 'customer') {
            $this->business_setting->updateOrCreate(['key_name' => 'customer_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'customer_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);

        } elseif ($request['app_type'] == 'provider') {
            $this->business_setting->updateOrCreate(['key_name' => 'provider_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'provider_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);

        } elseif ($request['app_type'] == 'serviceman') {
            $this->business_setting->updateOrCreate(['key_name' => 'serviceman_app_settings', 'settings_type' => 'app_settings'], [
                'key_name' => 'serviceman_app_settings',
                'live_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'test_values' => json_encode([
                    'min_version_for_android' => $request['min_version_for_android'],
                    'min_version_for_ios' => $request['min_version_for_ios'],
                ]),
                'settings_type' => 'app_settings',
                'mode' => 'live',
                'is_active' => $request->status ?? 0,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Update resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function social_login_config_set(Request $request): JsonResponse
    {
        $this->business_setting->updateOrCreate(['key_name' => $request['key'], 'settings_type' => 'social_login'], [
            'key_name' => $request['key'],
            'live_values' => $request['value'],
            'test_values' => $request['value'],
            'settings_type' => 'social_login',
            'mode' => 'live',
            'is_active' => 1,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    //customer settings

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function get_customer_settings(Request $request): View|Factory|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'wallet';
        $data_values = $this->business_setting->where('settings_type', 'customer_config')->get();
        return view('customermodule::admin.customer.settings', compact('web_page', 'data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function set_customer_settings(Request $request): RedirectResponse
    {
        if($request['web_page'] == 'wallet') {
            $validator = Validator::make($request->all(), [
                //wallet
                'customer_wallet' => 'in:0,1',
            ]);

            $filter = $validator->validated();
            $filter['customer_wallet'] = $request['customer_wallet']??0;
        }
        elseif ($request['web_page'] == 'loyalty_point') {
            $validator = Validator::make($request->all(), [
                //loyalty point
                'customer_loyalty_point' => 'in:0,1',
                'loyalty_point_value_per_currency_unit' => 'required',
                'loyalty_point_percentage_per_booking' => 'required',
                'min_loyalty_point_to_transfer' => 'required',
            ]);

            $filter = $validator->validated();
            $filter['customer_loyalty_point'] = $request['customer_loyalty_point']??0;
        }
        elseif ($request['web_page'] == 'referral_earning') {
            $validator = Validator::make($request->all(), [
                //referral earning
                'customer_referral_earning' => 'in:0,1',
                'referral_value_per_currency_unit' => 'required'
            ]);

            $filter = $validator->validated();
            $filter['customer_referral_earning'] = $request['customer_referral_earning']??0;
        } else {
            Toastr::success(DEFAULT_400['message']);
            return back();
        }

        foreach ($filter as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'customer_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }
}
