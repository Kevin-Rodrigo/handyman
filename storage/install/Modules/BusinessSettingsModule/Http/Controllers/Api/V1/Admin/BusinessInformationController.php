<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class BusinessInformationController extends Controller
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
    public function business_information_get(): JsonResponse
    {
        $data_values = $this->business_setting->where('settings_type', 'business_information')->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function business_information_set(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'business_name' => 'required',
            'business_phone' => 'required',
            'business_email' => 'required',
            'business_address' => 'required',
            'country_code' => 'required',
            'language_code' => 'array',
            'currency_code' => 'required',
            'currency_symbol_position' => 'required',
            'currency_decimal_point' => 'required',
            'time_zone' => 'required',
            'time_format' => '',
            'business_favicon' => 'image',
            'business_logo' => 'image',
            'default_commission' => 'required',
            'pagination_limit' => 'required',
            'footer_text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {

            if ($key == 'business_logo') {
                $file = $this->business_setting->where('key_name', 'business_logo')->first();
                $value = file_uploader('business/', 'png', $request->file('business_logo'), !empty($file->live_values) ? $file->live_values : '');
            }
            if ($key == 'business_favicon') {
                $file = $this->business_setting->where('key_name', 'business_favicon')->first();
                $value = file_uploader('business/', 'png', $request->file('business_favicon'), !empty($file->live_values) ? $file->live_values : '');
            }

            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'business_information',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function service_setup_get(): JsonResponse
    {
        $data_values = $this->business_setting->where('settings_type', 'service_setup')->get();
        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function service_setup_set(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'schedule_booking' => 'in:1,0',
            'provider_can_cancel_booking' => 'in:1,0',
            'serviceman_can_cancel_booking' => 'in:1,0',
            'admin_order_notification' => 'in:1,0',
            'sms_verification' => 'in:1,0',
            'email_verification' => 'in:1,0',
            'provider_self_registration' => 'in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key, 'settings_type' => 'service_setup'], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'service_setup',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function pages_setup_get(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|in:about_us,privacy_policy,terms_and_conditions,refund_policy,cancellation_policy'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $data_values = $this->business_setting->where('settings_type', 'pages_setup')
            ->where(['key_name' => $request['page_name']])->first();

        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function pages_setup_set(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'page_name' => 'required|in:about_us,privacy_policy,terms_and_conditions,refund_policy,cancellation_policy',
            'is_active' => 'required|in:0,1',
            'page_content' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->business_setting->updateOrCreate(['key_name' => $request['page_name'], 'settings_type' => 'pages_setup'], [
            'key_name' => $request['page_name'],
            'live_values' => $request['page_content'],
            'test_values' => null,
            'settings_type' => 'pages_setup',
            'mode' => 'live',
            'is_active' => $request['is_active'],
        ]);

        if (in_array($request['page_name'], ['privacy_policy', 'terms_and_conditions'])) {
            $message = translate('page_information_has_been_updated') . '!';
            topic_notification('customer', $request['page_name'], $message, 'def.png', null, $request['page_name']);
            topic_notification('provider-admin', $request['page_name'], $message, 'def.png', null, $request['page_name']);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
