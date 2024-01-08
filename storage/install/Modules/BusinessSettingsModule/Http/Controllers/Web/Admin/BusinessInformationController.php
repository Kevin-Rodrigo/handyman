<?php

namespace Modules\BusinessSettingsModule\Http\Controllers\Web\Admin;

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
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Madnest\Madzipper\Facades\Madzipper;
use Mockery\Exception;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Illuminate\Support\Facades\File;
use ZipArchive;

class BusinessInformationController extends Controller
{
    use ActivationClass;
    use FileManagerTrait;

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
    public function business_information_get(Request $request): Factory|View|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'business_setup';
        if ($web_page == 'business_setup') {
            $data_values = $this->business_setting->where('settings_type', 'business_information')->get();
        } elseif ($web_page == 'service_setup') {
            $data_values = $this->business_setting->where('settings_type', 'service_setup')->get();
        } elseif ($web_page == 'providers') {
            $data_values = $this->business_setting->where('settings_type', 'provider_config')->get();
        } elseif ($web_page == 'customers') {
            $data_values = $this->business_setting->where('settings_type', 'customer_config')->get();
        } elseif ($web_page == 'servicemen') {
            $data_values = $this->business_setting->where('settings_type', 'serviceman_config')->get();
        } elseif ($web_page == 'promotional_setup') {
            $data_values = $this->business_setting->where('settings_type', 'promotional_setup')->get();
        } elseif ($web_page == 'otp_login_setup') {
            $data_values = $this->business_setting->where('settings_type', 'otp_login_setup')->get();
        } elseif ($web_page == 'bookings') {
            $data_values = $this->business_setting->whereIn('settings_type', ['booking_setup', 'bidding_system'])->get();
        }

        return view('businesssettingsmodule::admin.business', compact('data_values', 'web_page'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function business_information_set(Request $request): JsonResponse
    {
        if (!$request->has('phone_number_visibility_for_chatting')) {
            $request['phone_number_visibility_for_chatting'] = '0';
        }
        if (!$request->has('direct_provider_booking')) {
            $request['direct_provider_booking'] = '0';
        }

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
            'business_favicon' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'business_logo' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'default_commission' => 'required',
            'pagination_limit' => 'required',
            'footer_text' => 'required',
            'cookies_text' => 'required',
            'minimum_withdraw_amount' => 'required',
            'maximum_withdraw_amount' => 'required',
            'phone_number_visibility_for_chatting' => 'required|in:0,1',
            'direct_provider_booking' => 'required|in:0,1',
            'forget_password_verification_method' => 'required|in:phone,email',
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

        session()->forget('pagination_limit');

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function otp_login_information_set(Request $request): JsonResponse|RedirectResponse
    {
        $validator = Validator::make($request->all(), [
            'temporary_login_block_time' => 'required',
            'maximum_login_hit' => 'required',
            'temporary_otp_block_time' => 'required',
            'maximum_otp_hit' => 'required',
            'otp_resend_time' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'otp_login_setup',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function set_bidding_system(Request $request): JsonResponse
    {
        if (!$request->has('bidding_status')) {
            $request['bidding_status'] = '0';
        }
        if (!$request->has('bid_offers_visibility_for_providers')) {
            $request['bid_offers_visibility_for_providers'] = '0';
        }

        $validator = Validator::make($request->all(), [
            'bidding_status' => 'required|in:0,1',
            'bidding_post_validity' => 'required',
            'bid_offers_visibility_for_providers' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'bidding_system',
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
     * @throws ValidationException
     */
    public function booking_setup_set(Request $request): JsonResponse
    {
        collect(['booking_otp', 'service_complete_photo_evidence', 'bidding_status', 'bid_offers_visibility_for_providers', 'booking_additional_charge'])
            ->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);

        $validator = Validator::make($request->all(), [
            'booking_otp' => 'required|in:0,1',
            'booking_additional_charge' => 'required|in:0,1',
            'service_complete_photo_evidence' => 'required|in:0,1',
            'min_booking_amount' => 'required|numeric|gte:0',
            'max_booking_amount' => 'required|numeric|gt:min_booking_amount',
            'additional_charge_label_name' => 'required|string',
            'additional_charge_fee_amount' => 'required|numeric|min:0',

            //bidding
            'bidding_post_validity' => 'required|numeric|gt:0',
            'bid_offers_visibility_for_providers' => 'required|in:0,1',
            'bidding_status' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $settings_type = in_array($key, ['bidding_post_validity', 'bid_offers_visibility_for_providers', 'bidding_status']) ? 'bidding_system' : 'booking_setup';

            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => $settings_type,
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function service_setup(Request $request): JsonResponse|RedirectResponse
    {
        collect([
            'phone_verification', 'email_verification', 'cash_after_service',
            'digital_payment', 'partial_payment', 'offline_payment', 'guest_checkout'
        ])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'phone_verification' => 'required|in:1,0',
            'email_verification' => 'required|in:1,0',
            'cash_after_service' => 'required|in:1,0',
            'digital_payment' => 'required|in:1,0',
            'partial_payment' => 'required|in:1,0',
            'partial_payment_combinator' => 'required|in:digital_payment,cash_after_service,offline_payment,all',
            'offline_payment' => 'required|in:1,0',
            'guest_checkout' => 'required|in:1,0',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'service_setup',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function servicemen(Request $request): JsonResponse|RedirectResponse
    {
        collect(['serviceman_can_edit_booking', 'serviceman_can_cancel_booking'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'serviceman_can_edit_booking' => 'required|in:0,1',
            'serviceman_can_cancel_booking' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
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

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function customer_setup(Request $request): JsonResponse|RedirectResponse
    {
        collect(['customer_wallet'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'customer_wallet' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
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

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function provider_setup(Request $request): JsonResponse|RedirectResponse
    {
        collect(['provider_can_cancel_booking', 'provider_can_edit_booking', 'provider_self_registration'])->each(fn($item, $key) => $request[$item] = $request->has($item) ? (int)$request[$item] : 0);
        $validator = Validator::make($request->all(), [
            'provider_can_cancel_booking' => 'required|in:0,1',
            'provider_can_edit_booking' => 'required|in:0,1',
            'provider_self_registration' => 'required|in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            $this->business_setting->updateOrCreate(['key_name' => $key], [
                'key_name' => $key,
                'live_values' => $value,
                'test_values' => $value,
                'settings_type' => 'provider_config',
                'mode' => 'live',
                'is_active' => 1,
            ]);
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }


    /**
     * Update resource.
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update_action_status(Request $request): JsonResponse
    {
        $request[$request['key']] = $request['value'];

        $validator = Validator::make($request->all(), [
            'schedule_booking' => 'in:1,0',
            'provider_can_cancel_booking' => 'in:1,0',
            'serviceman_can_cancel_booking' => 'in:1,0',
            'admin_order_notification' => 'in:1,0',
            'phone_verification' => 'in:1,0',
            'email_verification' => 'in:1,0',
            'provider_self_registration' => 'in:1,0',
            'guest_checkout' => 'in:1,0',
            'booking_additional_charge' => 'in:1,0',

            //bidding
            'bidding_status' => 'in:0,1',

            //payment
            'cash_after_service' => 'in:0,1',
            'digital_payment' => 'in:0,1',
            'wallet_payment' => 'in:0,1',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        foreach ($validator->validated() as $key => $value) {
            if ($key != 'phone_verification' && $key != 'email_verification') {
                $this->business_setting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                    'key_name' => $key,
                    'live_values' => $value,
                    'test_values' => $value,
                    'is_active' => $value,
                    'settings_type' => $request['settings_type'],
                    'mode' => 'live',
                ]);
            } else {
                if ($key == 'phone_verification') {
                    $this->business_setting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                        'key_name' => $key,
                        'live_values' => $value,
                        'test_values' => $value,
                        'is_active' => $value,
                        'settings_type' => $request['settings_type'],
                        'mode' => 'live',
                    ]);
                    if ($value == 1) {
                        $this->business_setting->updateOrCreate(['key_name' => 'email_verification', 'settings_type' => $request['settings_type']], [
                            'key_name' => 'email_verification',
                            'live_values' => (int)!$value,
                            'test_values' => (int)!$value,
                            'is_active' => (int)!$value,
                            'settings_type' => $request['settings_type'],
                            'mode' => 'live',
                        ]);
                    }
                } else if ($key == 'email_verification') {
                    $this->business_setting->updateOrCreate(['key_name' => $key, 'settings_type' => $request['settings_type']], [
                        'key_name' => $key,
                        'live_values' => $value,
                        'test_values' => $value,
                        'is_active' => $value,
                        'settings_type' => $request['settings_type'],
                        'mode' => 'live',
                    ]);
                    if ($value == 1) {
                        $this->business_setting->updateOrCreate(['key_name' => 'phone_verification', 'settings_type' => $request['settings_type']], [
                            'key_name' => 'phone_verification',
                            'live_values' => (int)!$value,
                            'test_values' => (int)!$value,
                            'is_active' => (int)!$value,
                            'settings_type' => $request['settings_type'],
                            'mode' => 'live',
                        ]);
                    }
                }
            }
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws ValidationException
     */
    public function promotion_setup_set(Request $request): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'bearer' => 'required|in:admin,provider,both',
        ]);

        if ($request['bearer'] != 'both' && $request['bearer'] == 'admin') {
            $request['admin_percentage'] = 100;
            $request['provider_percentage'] = 0;
        }
        if ($request['bearer'] != 'both' && $request['bearer'] == 'provider') {
            $request['admin_percentage'] = 0;
            $request['provider_percentage'] = 100;
        }

        $validator = Validator::make($request->all(), [
            'bearer' => 'in:admin,provider,both',
            'admin_percentage' => $request['bearer'] == 'both' ? 'min:1|max:99' : '',
            'provider_percentage' => $request['bearer'] == 'both' ? 'min:1|max:99' : '',
            'type' => 'in:discount,campaign,coupon',
        ]);

        if ($validator->fails()) {
            Toastr::error(DEFAULT_FAIL_200['message']);
            return back();
        }


        $this->business_setting->updateOrCreate(['key_name' => $request['type'] . '_cost_bearer', 'settings_type' => 'promotional_setup'], [
            'key_name' => $request['type'] . '_cost_bearer',
            'live_values' => $validator->validated(),
            'test_values' => $validator->validated(),
            'is_active' => 1,
            'settings_type' => 'promotional_setup',
            'mode' => 'live',
        ]);

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function pages_setup_get(Request $request): View|Factory|Application
    {
        $web_page = $request->has('web_page') ? $request['web_page'] : 'about_us';
        $data_values = $this->business_setting->where('settings_type', 'pages_setup')->orderBy('key_name')->get();
        return view('businesssettingsmodule::admin.page-settings', compact('data_values', 'web_page'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function pages_setup_set(Request $request): JsonResponse|\Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'page_name' => 'required|in:about_us,privacy_policy,terms_and_conditions,refund_policy,cancellation_policy',
            'page_content' => ''
        ]);

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

            $tnc_update = business_config('tnc_update', 'notification_settings');
            if ($request['page_name'] == 'terms_and_conditions' && isset($tnc_update) && $tnc_update->live_values['push_notification_tnc_update'] == 1 && $request['is_active'] == 1) {
                topic_notification('customer', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-admin', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-serviceman', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
            }

            $pp_update = business_config('pp_update', 'notification_settings');
            if ($request['page_name'] == 'privacy_policy' && isset($pp_update) && $pp_update->live_values['push_notification_pp_update'] == 1) {
                topic_notification('customer', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-admin', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
                topic_notification('provider-serviceman', translate($request['page_name']), $message, 'def.png', null, $request['page_name']);
            }
        }

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function gallery_setup_get($folder_path = "cHVibGlj"): View|Factory|Application
    {
        $file = Storage::files(base64_decode($folder_path));
        $directories = Storage::directories(base64_decode($folder_path));

        $folders = $this->format_file_and_folders($directories, 'folder');
        $files = $this->format_file_and_folders($file, 'file');
        // dd($files);
        $data = array_merge($folders, $files);
        return view('businesssettingsmodule::admin.gallery-settings', compact('data', 'folder_path'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gallery_image_upload(Request $request)
    {
        if (env('APP_MODE') == 'demo') {
            Toastr::info(translate('messages.upload_option_is_disable_for_demo'));
            return back();
        }
        $request->validate([
            'images' => 'required_without:file',
            'images.*' => 'max:10000',
            'file' => 'required_without:images|mimes:zip',
            'path' => 'required',
        ]);
        if ($request->hasfile('images')) {
            $images = $request->file('images');

            foreach ($images as $image) {
                $name = $image->getClientOriginalName();
                Storage::disk('local')->put($request->path . '/' . $name, file_get_contents($image));
            }
        }
        if ($request->hasfile('file')) {
            $file = $request->file('file');
            $name = $file->getClientOriginalName();

            Madzipper::make($file)->extractTo('storage/app/' . $request->path);
            // Storage::disk('local')->put($request->path.'/'. $name, file_get_contents($file));

        }
        Toastr::success(translate('image_uploaded_successfully'));
        return back()->with('success', translate('image_uploaded_successfully'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function gallery_image_remove($file_path)
    {
        Storage::disk('local')->delete(base64_decode($file_path));
        Toastr::success(translate('image_deleted_successfully'));
        return back()->with('success', translate('image_deleted_successfully'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function gallery_image_download($file_name)
    {
        return Storage::download(base64_decode($file_name));
    }

    public function download_public_directory()
    {
        if (!class_exists('ZipArchive')) {
            Toastr::error(translate('The ZipArchive class is not available'));
            return back();
        }

        if (!extension_loaded('zip')) {
            Toastr::error(translate('The zip extension is not enabled'));
            return back();
        }

        $zipFileName = 'public.zip';

        $zip = new ZipArchive;

        if ($zip->open($zipFileName, ZipArchive::CREATE) === true) {
            $files = Storage::disk('public')->allFiles();
            foreach ($files as $file) {
                $filePath = storage_path('app/public/' . $file);
                $relativePath = str_replace('public/', '', $file);
                $zip->addFile($filePath, $relativePath);
            }

            $zip->close();

            $response = new BinaryFileResponse($zipFileName);
            $response->deleteFileAfterSend(true);
            return $response;
        } else {
            Toastr::error(translate('Failed to create zip archive'));
            return back();
        }
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function get_database_backup(): View|Factory|Application
    {
        if (!File::exists(storage_path('backup'))) {
            File::makeDirectory(storage_path('backup'), 0777, true);
        }
        $files = File::files('storage/backup');

        $filenames = [];
        foreach ($files as $file) {
            $filenames[] = $file->getFilename();
        }

        return view('businesssettingsmodule::admin.database-backup', compact('filenames'));
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete_database_backup($file_name)
    {
        $file = storage_path('backup/' . $file_name);
        if (File::exists($file)) {
            File::delete($file);
        }
        Toastr::success(DEFAULT_DELETE_200['message']);
        return back();
    }

    /**
     * Backup of the resource.
     */
    public function backup_database()
    {
        //take backup
        Artisan::call('database:backup');

        //move file
        if (!File::exists(storage_path('backup'))) {
            File::makeDirectory(storage_path('backup'), 0777, true);
        }
        $sql_file_name = 'database_backup_' . date("Y-m-d_H-i") . '.sql';

        $file = base_path($sql_file_name);
        $destination = storage_path('backup/' . $sql_file_name);
        File::move($file, $destination);

        Toastr::success(translate('Database backup has been completed successfully'));
        return back();
    }

    /**
     * Restore the resource.
     */
    public function restore_database_backup($file_name)
    {
        $file = storage_path('backup/' . $file_name);
        if (!File::exists($file)) {
            Toastr::error(translate('File does not exists'));
            return back();
        }

        try {
            //db operations
            Artisan::call('db:wipe');
            DB::unprepared(file_get_contents($file));

            Toastr::success(translate('Database restored successfully'));
            return back();

        } catch (\Exception $exception) {
            Toastr::success(translate('Database restored failed'));
            return back();
        }

    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return BinaryFileResponse | RedirectResponse
     */
    public function download($file_name): BinaryFileResponse|RedirectResponse
    {
        $file = storage_path('backup/' . $file_name);
        if (File::exists($file)) {
            return response()->download($file);
        }

        Toastr::error(translate('File does not exists'));
        return back();
    }

}
