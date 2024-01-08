<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Admin;

use App\Traits\ActivationClass;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Filesystem\FilesystemManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Ramsey\Uuid\Uuid;

class LandingPageController extends Controller
{
    use ActivationClass;

    private BusinessSettings $business_setting;

    public function __construct(BusinessSettings $business_setting)
    {
        $this->business_setting = $business_setting;

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
    public function landing_information_get(Request $request): Factory|View|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'text_setup';
        //$pages = ['text_setup', 'button_and_links', 'speciality', 'testimonial', 'images', 'background'];
        $data_values = $this->business_setting->where('settings_type', 'landing_' . $web_page)->get();
        return view('businesssettingsmodule::admin.landing-page', compact('data_values', 'web_page'));
    }

    /**
     * Display a listing of the resource.
     */
    public function landing_information_set(Request $request): JsonResponse|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'top_title' => 'string',
            'top_description' => 'string',
            'top_sub_title' => 'string',
            'mid_title' => 'string',
            'about_us_title' => 'string',
            'about_us_description' => 'string',
            'registration_title' => 'string',
            'registration_description' => 'string',
            'bottom_title' => 'string',
            'bottom_description' => 'string',

            'app_url_playstore' => 'string',
            'app_url_appstore' => 'string',
            'web_url' => 'string',

            'meta_title' => 'string',
            'meta_description' => 'string',
            'meta_image' => 'image',

            'header_background' => 'string',
            'body_background' => 'string',
            'footer_background' => 'string',

            'web_top_title' => 'string',
            'web_top_description' => 'string',
            'web_mid_title' => 'string',
            'mid_sub_title_1' => 'string',
            'mid_sub_description_1' => 'string',
            'mid_sub_title_2' => 'string',
            'mid_sub_description_2' => 'string',
            'mid_sub_title_3' => 'string',
            'mid_sub_description_3' => 'string',
            'download_section_title' => 'string',
            'download_section_description' => 'string',
            'web_bottom_title' => 'string',
            'testimonial_title' => 'string',

            'media' => 'in:facebook,instagram,linkedin,twitter,youtube',
            'link' => '',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 200);
        }
        $array = [];
        if ($request['web_page'] == 'speciality') {
            $data = $this->business_setting->where('settings_type', 'landing_speciality')->first();
            if (isset($data)) {
                $array = $data['live_values'];
            }
            $array[] = [
                'id' => Uuid::uuid4(),
                'title' => $request['title'],
                'description' => $request['description'],
                'image' => file_uploader('landing-page/', 'png', $request->file('image'))
            ];
            $request['speciality'] = $array;
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'title', 'description', 'image', 'web_page']);
        }
        elseif ($request['web_page'] == 'testimonial') {
            $data = $this->business_setting->where('settings_type', 'landing_testimonial')->first();
            if (isset($data)) {
                $array = $data['live_values'];
            }
            $array[] = [
                'id' => Uuid::uuid4(),
                'name' => $request['name'],
                'designation' => $request['designation'],
                'review' => $request['review'],
                'image' => file_uploader('landing-page/', 'png', $request->file('image'))
            ];
            $request['testimonial'] = $array;
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'name', 'designation', 'review', 'image', 'web_page']);
        }
        elseif ($request['web_page'] == 'features') {
            $data = $this->business_setting->where('settings_type', 'landing_features')->first();
            if (isset($data)) {
                $array = $data['live_values'];
            }
            $array[] = [
                'id' => Uuid::uuid4(),
                'title' => $request['title'],
                'sub_title' => $request['sub_title'],
                'image_1' => file_uploader('landing-page/', 'png', $request->file('image_1')),
                'image_2' => file_uploader('landing-page/', 'png', $request->file('image_2')),
            ];
            $request['features'] = $array;
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'title', 'sub_title', 'image_1', 'image_2', 'web_page']);
        }
        elseif ($request['web_page'] == 'images') {
            $keys = ['top_image_1', 'top_image_2', 'top_image_3', 'top_image_4', 'about_us_image', 'service_section_image', 'provider_section_image'];
            $image = 'def.png';
            $image_key = '';
            foreach ($keys as $key) {
                if ($request->has($key)) {
                    $value = $this->business_setting->where('key_name', $key)->first();
                    if (isset($value)) {
                        file_remover('landing-page/', $value['live_values']);
                    }
                    $image = file_uploader('landing-page/', 'png', $request->file($key));
                    $image_key = $key;
                }
            }
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'web_page', $image_key]);
            $filter[$image_key] = $image;
        }
        elseif ($request['web_page'] == 'web_app_image') {
            $keys = ['support_section_image', 'download_section_image', 'feature_section_image'];
            $image = 'def.png';
            $image_key = '';
            foreach ($keys as $key) {
                if ($request->has($key)) {
                    $value = $this->business_setting->where('key_name', $key)->first();
                    if (isset($value)) {
                        file_remover('landing-page/web/', $value['live_values']);
                    }
                    $image = file_uploader('landing-page/web/', 'png', $request->file($key));
                    $image_key = $key;
                }
            }
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'web_page', $image_key]);
            $filter[$image_key] = $image;
        }
        elseif ($request['web_page'] == 'social_media') {
            $data = $this->business_setting->where('settings_type', 'landing_social_media')->first();
            if (isset($data)) {
                $array = $data['live_values'];
            }
            $array[] = [
                'id' => Uuid::uuid4(),
                'media' => $request['media'],
                'link' => $request['link']
            ];
            $request['social_media'] = $array;
            $page = $request['web_page'];
            $filter = $request->except(['_method', '_token', 'media', 'link', 'web_page']);
        }
        else {
            $page = $request['web_page'];
            $filter = $validator->validated();
        }

        foreach ($filter as $key => $value) {
            if ($key == 'meta_image') {
                $value = $this->business_setting->where('key_name', $key)->first();
                if (isset($value)) {
                    file_remover('landing-page/meta/', $value['live_values']);
                }
                $image = file_uploader('landing-page/meta/', 'png', $request->file('meta_image'));

            }

            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $key == 'meta_image' ? $image : $value,
                'test_values' => $key == 'meta_image' ? $image : $value,
                'settings_type' => 'landing_' . $page,
                'mode' => 'live',
                'is_active' => is_null($request[$key . '_is_active']) && $request[$key . '_is_active'] == 0 ? 0 : 1,
            ]);
        }

        if ($request->ajax()) {
            return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
        }
        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }


    /**
     * Display a listing of the resource.
     * @param $page
     * @param $id
     * @return RedirectResponse
     */
    public function landing_information_delete($page, $id): RedirectResponse
    {
        $array = [];
        if ($page == 'speciality') {
            $data = $this->business_setting->where('settings_type', 'landing_speciality')->first();
            foreach ($data->live_values as $value) {
                if ($value['id'] != $id) {
                    $array[] = $value;
                } else {
                    file_remover('landing-page/', $value['image']);
                }
            }
        } elseif ($page == 'testimonial') {
            $data = $this->business_setting->where('settings_type', 'landing_testimonial')->first();
            foreach ($data->live_values as $value) {
                if ($value['id'] != $id) {
                    $array[] = $value;
                } else {
                    file_remover('landing-page/', $value['image']);
                }
            }
        }  elseif ($page == 'social_media') {
            $data = $this->business_setting->where('settings_type', 'landing_social_media')->first();
            foreach ($data->live_values as $value) {
                if ($value['id'] != $id) {
                    $array[] = $value;
                }
            }
        } elseif ($page == 'features') {
            $data = $this->business_setting->where('settings_type', 'landing_features')->first();
            foreach ($data->live_values as $value) {
                if ($value['id'] != $id) {
                    $array[] = $value;
                } else {
                    file_remover('landing-page/', $value['image_1']);
                    file_remover('landing-page/', $value['image_2']);
                }
            }
        }

        $this->business_setting->updateOrCreate(['key_name' => $page], [
            'key_name' => $page,
            'live_values' => $array,
            'test_values' => $array,
            'settings_type' => 'landing_' . $page,
            'mode' => 'live',
            'is_active' => 1,
        ]);

        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function service_setup_set(Request $request): JsonResponse
    {
        $request[$request['key']] = $request['value'];

        $validator = Validator::make($request->all(), [
            'schedule_booking' => 'in:1,0',
            'provider_can_cancel_booking' => 'in:1,0',
            'serviceman_can_cancel_booking' => 'in:1,0',
            'admin_order_notification' => 'in:1,0',
            'sms_verification' => 'in:1,0',
            'email_verification' => 'in:1,0',
            'provider_self_registration' => 'in:1,0'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key, 'settings_type' => 'service_setup'], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'is_active' => $value,
                'settings_type' => 'service_setup',
                'mode' => 'live',
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function pages_setup_get(Request $request): View|Factory|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'about_us';
        return view('businesssettingsmodule::admin.page-settings', compact( 'web_page'));
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
            'page_content' => ''
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
            'is_active' => $request['is_active'] ?? 0,
        ]);

        if (in_array($request['page_name'], ['privacy_policy', 'terms_and_conditions'])) {
            $message = translate('page_information_has_been_updated') . '!';
            topic_notification('customer', $request['page_name'], $message, 'def.png', null, $request['page_name']);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }
}
