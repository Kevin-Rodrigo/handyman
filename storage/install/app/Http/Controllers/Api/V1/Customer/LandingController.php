<?php

namespace App\Http\Controllers\Api\V1\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class LandingController extends Controller
{

    private BusinessSettings $business_setting;

    public function __construct(BusinessSettings $business_setting)
    {
        $this->business_setting = $business_setting;
    }

    public function index()
    {
        $data = [];

        $keys = ['top_image_1', 'top_image_2', 'top_image_3', 'top_image_4'];
        $values = $this->business_setting->select('key_name', 'live_values')->whereIn('key_name', $keys)->get();
        $data['banner_images'] = $values;

        $values = $this->business_setting->select('key_name', 'live_values')->where('settings_type', 'landing_web_app')->get();
        $data['text_content'] = $values;

        $values = $this->business_setting->select('key_name', 'live_values')->where('settings_type', 'landing_web_app_image')->get();
        $data['image_content'] = $values;

        $values = $this->business_setting->where('key_name', 'testimonial')->first();
        $data['testimonial'] = isset($values) && !is_null($values->live_values) ? $values->live_values : null;

        $values = $this->business_setting->where('key_name', 'social_media')->first();
        $data['social_media'] = isset($values) && !is_null($values->live_values) ? $values->live_values : null;

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }
}
