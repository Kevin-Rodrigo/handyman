<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
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
     * @return JsonResponse
     */
    public function notification_settings_get(): JsonResponse
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['notification_settings', 'notification_messages'])->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function notification_settings_set(Request $request): JsonResponse
    {
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
            'email_pp_update' => 'in:0,1',
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
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = ['booking', 'subscription', 'rating_review', 'tnc_update', 'pp_update'];
        foreach ($keys as $key => $value) {
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

        $booking_keys = ['booking_place', 'booking_accepted', 'booking_refund', 'booking_cancel', 'booking_service_complete'];
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
     * @return JsonResponse
     */
    public function email_config_get(): JsonResponse
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['email_config'])->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
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
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
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
     * @return JsonResponse
     */
    public function third_party_config_get(): JsonResponse
    {
        $data_values = $this->business_setting->whereIn('settings_type', ['third_party'])->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
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
            'party_name' => 'required|in:google_map,push_notification,recaptcha'
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
        }

        $validator = Validator::make($request->all(), array_merge($validation, $additional_data));

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->business_setting->updateOrCreate(['key_name' => $request['party_name'], 'settings_type' => 'third_party'], [
            'key_name' => $request['party_name'],
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'settings_type' => 'third_party',
            'mode' => 'live',
            'is_active' => 1,
        ]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
