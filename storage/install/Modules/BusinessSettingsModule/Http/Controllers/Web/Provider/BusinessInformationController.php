<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Provider;

use App\Traits\ActivationClass;
use App\Traits\FileManagerTrait;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\ProviderManagement\Entities\ProviderSetting;

class BusinessInformationController extends Controller
{
    use ActivationClass;
    use FileManagerTrait;

    private BusinessSettings $business_setting;
    private ProviderSetting $provider_setting;

    public function __construct(BusinessSettings $business_setting, ProviderSetting $provider_setting)
    {
        $this->business_setting = $business_setting;
        $this->provider_setting = $provider_setting;

        if (request()->isMethod('get')) {
            $response = $this->actch();
            $data = json_decode($response->getContent(), true);
            if (!$data['active']) {
                return Redirect::away(base64_decode('aHR0cHM6Ly82YW10ZWNoLmNvbS9zb2Z0d2FyZS1hY3RpdmF0aW9u'))->send();
            }
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function business_information_get(Request $request): Factory|View|Application
    {
        if ($this->provider_setting->where(['key_name' => 'provider_serviceman_can_edit_booking', 'settings_type'=> 'serviceman_config', 'provider_id' => auth()->user()->provider->id])->first() == false) {
            $this->provider_setting->updateOrCreate(['key_name' => 'provider_serviceman_can_edit_booking', 'settings_type'=> 'serviceman_config'], [
                'provider_id' => auth()->user()->provider->id,
                'live_values' => 0,
                'test_values' => 0
            ]);
        }

        if ($this->provider_setting->where(['key_name' => 'provider_serviceman_can_cancel_booking', 'settings_type'=> 'serviceman_config', 'provider_id' => auth()->user()->provider->id])->first() == false) {
            $this->provider_setting->updateOrCreate(['key_name' => 'provider_serviceman_can_cancel_booking', 'settings_type'=> 'serviceman_config'], [
                'provider_id' => auth()->user()->provider->id,
                'live_values' => 0,
                'test_values' => 0
            ]);
        }

        $data_values = $this->provider_setting->where('settings_type', 'serviceman_config')->get();
        return view('businesssettingsmodule::provider.business', compact('data_values'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function business_information_set(Request $request): JsonResponse|RedirectResponse
    {
        collect(['provider_serviceman_can_edit_booking', 'provider_serviceman_can_cancel_booking'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'provider_serviceman_can_edit_booking' => 'required|in:0,1',
            'provider_serviceman_can_cancel_booking' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->provider_setting->updateOrCreate(['key_name' => $key, 'provider_id' => auth()->user()->provider->id, 'settings_type' => 'serviceman_config'], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'serviceman_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

}
