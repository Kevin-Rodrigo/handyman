<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Api\V1\Provider;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\ProviderManagement\Entities\ProviderSetting;

class BusinessInformationController extends Controller
{
    private ProviderSetting $provider_setting;

    public function __construct(ProviderSetting $provider_setting)
    {
        $this->provider_setting = $provider_setting;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function business_settings_get(Request $request): JsonResponse
    {
        $request['key'] = ['provider_serviceman_can_cancel_booking', 'provider_serviceman_can_edit_booking'];

        $data_values = $this->provider_setting
            ->select('key_name', 'live_values', 'test_values', 'mode')
            ->when(!is_null($request['key']), fn ($query) => $query->whereIn('key_name', $request['key'])->where('provider_id', $request->user()->provider->id))
            ->get();

        return response()->json(response_formatter(DEFAULT_200, $data_values), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function business_settings_set(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'data' => 'required',
            'data.*.key' => 'required|string',
            'data.*.value' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach (collect(json_decode($request['data'], true)) as $key => $item) {
            $settings_type = in_array($item['key'], ['provider_serviceman_can_edit_booking', 'provider_serviceman_can_cancel_booking']) ? 'serviceman_config' : null;

            if (!is_null($settings_type)) {
                $this->provider_setting->updateOrCreate(['key_name' => $item['key'], 'provider_id' => $request->user()->provider->id], [
                    'key_name' => $item['key'],
                    'live_values' => $item['value'],
                    'test_values' => $item['value'],
                    'settings_type' => $settings_type,
                    'mode' => 'live',
                    'is_active' => 1,
                ]);
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
