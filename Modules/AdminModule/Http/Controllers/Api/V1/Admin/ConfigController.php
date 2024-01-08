<?php

namespace Modules\AdminModule\Http\Controllers\Api\V1\Admin;

use DateTimeZone;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\ProviderManagement\Entities\Provider;

class ConfigController extends Controller
{
    private $provider;
    private $google_map;
    private $google_map_base_api;

    public function __construct(Provider $provider)
    {
        $this->provider = $provider;
        $this->google_map = business_config('google_map', 'third_party');
        $this->google_map_base_api = 'https://maps.googleapis.com/maps/api';
    }

    /**
     * Display a listing of the resource.
     * @return JsonResponse
     */
    public function config(): JsonResponse
    {
        return response()->json(response_formatter(DEFAULT_200, [
            'map_api_key' => $this->google_map,
            'image_base_url' => asset('storage/app/public'),
            'languages' => LANGUAGES,
            'currencies' => CURRENCIES,
            'countries' => COUNTRIES,
            'system_modules' => SYSTEM_MODULES,
            'time_zones' => DateTimeZone::listIdentifiers(),
            'recaptcha' => (business_config('recaptcha', 'third_party'))->live_values ?? null,
            'pagination_limit' => (business_config('pagination_limit', 'business_information'))->live_values ?? null,
            'footer_text' => (business_config('footer_text', 'business_information'))->live_values ?? null,
            'currency_decimal_point' => (business_config('currency_decimal_point', 'business_information'))->live_values ?? null,
            'currency_symbol_position' => (business_config('currency_symbol_position', 'business_information'))->live_values ?? null,
            'business_name' => (business_config('business_name', 'business_information'))->live_values ?? null,
            'logo' => (business_config('business_logo', 'business_information'))->live_values ?? null,
            'favicon' => (business_config('business_favicon', 'business_information'))->live_values ?? null,
            'country_code' => (business_config('country_code', 'business_information'))->live_values ?? null,
            'business_address' => (business_config('business_address', 'business_information'))->live_values ?? null,
            'business_phone' => (business_config('business_phone', 'business_information'))->live_values ?? null,
            'base_url' => url('/') . '/api/v1/',
            'currency_code' => (business_config('currency_code', 'business_information'))->live_values ?? null,
            'about_us' => route('about-us'),
            'privacy_policy' => route('privacy-policy'),
            'terms_and_conditions' => route('terms-and-conditions'),
            'refund_policy' => route('refund-policy'),
            'return_policy' => route('return-policy'),
            'cancellation_policy' => route('cancellation-policy'),
            'default_location' => ['default' => [
                'lat' => 23.812492,
                'lon' => 90.368620
            ]],
            'app_url_android' => '',
            'app_url_ios' => '',
            'sms_verification' => (business_config('sms_verification', 'service_setup'))->live_values ?? null,
            'email_verification' => (business_config('email_verification', 'service_setup'))->live_values ?? null,
        ]), 200);
    }

    public function counts(): JsonResponse
    {
        $onboarding_count = $this->provider->ofStatus(0)->count();
        $denied_count = $this->provider->ofStatus(2)->count();
        $booking_overview = DB::table('bookings')
            ->select('booking_status', DB::raw('count(*) as total'))
            ->groupBy('booking_status')
            ->get();

        return response()->json(response_formatter(DEFAULT_200, [
            'onboarding_count' => $onboarding_count,
            'onboarding_denied_count' => $denied_count,
            'booking_count' => $booking_overview
        ]), 200);
    }
}
