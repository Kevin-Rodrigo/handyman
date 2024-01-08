<?php

namespace Modules\CustomerModule\Http\Controllers\Api\V1\Customer;

use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Entities\User;
use Modules\ZoneManagement\Entities\Zone;
use Stevebauman\Location\Facades\Location;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class ConfigController extends Controller
{
    private $google_map;
    private $google_map_base_api;

    function __construct()
    {
        $this->google_map = business_config('google_map', 'third_party');
        $this->google_map_base_api = 'https://maps.googleapis.com/maps/api';
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function configuration(Request $request): JsonResponse
    {
        $location = Location::get($request->ip());

        $playstore = business_config('app_url_playstore', 'landing_button_and_links');
        $appstore = business_config('app_url_appstore', 'landing_button_and_links');

        $google_social_login = business_config('google_social_login', 'social_login');
        $facebook_social_login = business_config('facebook_social_login', 'social_login');
        $apple_social_login = business_config('apple_login', 'third_party');

        //payment gateways
        $is_published = 0;
        try {
            $full_data = include('Modules/Gateways/Addon/info.php');
            $is_published = $full_data['is_published'] == 1 ? 1 : 0;
        } catch (\Exception $exception) {}

        $payment_gateways = collect($this->getPaymentMethods())
            ->filter(function ($query) use ($is_published) {
                if (!$is_published) {
                    return in_array($query['gateway'], array_column(PAYMENT_METHODS, 'key'));
                } else return $query;
            })->map(function ($query) {
                $query['label'] = ucwords(str_replace('_', ' ', $query['gateway']));
                return $query;
            })->values();


        return response()->json(response_formatter(DEFAULT_200, [
            'business_name' => (business_config('business_name', 'business_information'))->live_values ?? null,
            'logo' => (business_config('business_logo', 'business_information'))->live_values ?? null,
            'country_code' => (business_config('country_code', 'business_information'))->live_values ?? null,
            'business_address' => (business_config('business_address', 'business_information'))->live_values ?? null,
            'business_phone' => (business_config('business_phone', 'business_information'))->live_values ?? null,
            'business_email' => (business_config('business_email', 'business_information'))->live_values ?? null,
            'base_url' => url('/') . 'api/v1/',
            'currency_decimal_point' => (business_config('currency_decimal_point', 'business_information'))->live_values ?? null,
            'currency_code' => (business_config('currency_code', 'business_information'))->live_values ?? null,
            'currency_symbol_position' => (business_config('currency_symbol_position', 'business_information'))->live_values ?? null,
            'about_us' => route('about-us'),
            'privacy_policy' => route('privacy-policy'),
            'terms_and_conditions' => (business_config('terms_and_conditions', 'pages_setup'))->is_active ? route('terms-and-conditions') : "",
            'refund_policy' => (business_config('refund_policy', 'pages_setup'))->is_active ? route('refund-policy') : "",
            'cancellation_policy' => (business_config('cancellation_policy', 'pages_setup'))->is_active ? route('cancellation-policy') : "",
            // 'default_location' => ['default' => [
            //     'lat' => $location->latitude,
            //     'lon' => $location->longitude
            // ]],
            'user_location_info' => $location,
            'app_url_android' => '',
            'app_url_ios' => '',
            //'sms_verification' => (business_config('sms_verification', 'service_setup'))->live_values ?? null,
            //'email_verification' => (business_config('email_verification', 'service_setup'))->live_values ?? null,
            'map_api_key' => $this->google_map,
            'image_base_url' => asset('storage/app/public'),
            'pagination_limit' => 20,
            'languages' => LANGUAGES,
            'currencies' => CURRENCIES,
            'countries' => COUNTRIES,
            'time_zones' => DateTimeZone::listIdentifiers(),
            'payment_gateways' => $payment_gateways,
            'footer_text' => (business_config('footer_text', 'business_information'))->live_values ?? null,
            'cookies_text' => (business_config('cookies_text', 'business_information'))->live_values ?? null,
            'admin_details' => User::select('id', 'first_name', 'last_name', 'profile_image')->where('user_type', ADMIN_USER_TYPES[0])->first(),
            'min_versions' => json_decode((business_config('customer_app_settings', 'app_settings'))->live_values ?? null),
            'app_url_playstore' => $playstore->is_active ? $playstore->live_values : null,
            'app_url_appstore' => $appstore->is_active ? $appstore->live_values : null,
            'web_url' => (business_config('web_url', 'landing_button_and_links'))->is_active == '1' ? (business_config('web_url', 'landing_button_and_links'))->live_values : null,
            'google_social_login' => (int) ($google_social_login->live_values ?? 0),
            'facebook_social_login' => (int) ($facebook_social_login->live_values ?? 0),
            'apple_social_login' => (int) ($apple_social_login->is_active ?? 0),
            'phone_number_visibility_for_chatting' => (int)((business_config('phone_number_visibility_for_chatting', 'business_information'))->live_values ?? 0),
            'wallet_status' => (int)((business_config('customer_wallet', 'customer_config'))->live_values ?? 0),
            'loyalty_point_status' => (int)((business_config('customer_loyalty_point', 'customer_config'))->live_values ?? 0),
            'referral_earning_status' => (int)((business_config('customer_referral_earning', 'customer_config'))->live_values ?? 0),
            'direct_provider_booking' => (int)((business_config('direct_provider_booking', 'business_information'))->live_values ?? 0),
            'bidding_status' => (int)((business_config('bidding_status', 'bidding_system'))->live_values ?? 0),
            'phone_verification' => (int)((business_config('phone_verification', 'service_setup'))->live_values ?? 0),
            'email_verification' => (int)((business_config('email_verification', 'service_setup'))->live_values ?? 0),
            'forget_password_verification_method' => (business_config('forget_password_verification_method', 'business_information'))->live_values ?? null,
            'cash_after_service' => (int)((business_config('cash_after_service', 'service_setup'))->live_values ?? 0),
            'digital_payment' => (int)((business_config('digital_payment', 'service_setup'))->live_values ?? 0),
            'wallet_payment' => (int)((business_config('wallet_payment', 'service_setup'))->live_values ?? 0),
            'social_media' => (business_config('social_media', 'landing_social_media'))->live_values ?? null,
            'otp_resend_time' => (int) (business_config('otp_resend_time', 'otp_login_setup'))?->live_values ?? null,
            'max_booking_amount' => (int) (business_config('max_booking_amount', 'booking_setup'))?->live_values ?? null,
            'min_booking_amount' => (int) (business_config('min_booking_amount', 'booking_setup'))?->live_values ?? null,
            'guest_checkout' => (int) (business_config('guest_checkout', 'service_setup'))?->live_values ?? null,
            'partial_payment' => (int) (business_config('partial_payment', 'service_setup'))?->live_values ?? null,
            'booking_additional_charge' => (int) (business_config('booking_additional_charge', 'booking_setup'))?->live_values ?? null,
            'additional_charge_label_name' => (string) (business_config('additional_charge_label_name', 'booking_setup'))?->live_values ?? null,
            'additional_charge_fee_amount' => (int) (business_config('additional_charge_fee_amount', 'booking_setup'))?->live_values ?? null,
            'offline_payment' => (int) (business_config('offline_payment', 'service_setup'))?->live_values ?? null,
            'partial_payment_combinator' => (string) (business_config('partial_payment_combinator', 'service_setup'))?->live_values ?? null,
            'provider_self_registration' => (int) business_config('provider_self_registration', 'provider_config')?->live_values,
        ]), 200);
    }

    public function pages(): JsonResponse
    {
        return response()->json(response_formatter(DEFAULT_200, [
            'about_us' => business_config('about_us', 'pages_setup'),
            'terms_and_conditions' => business_config('terms_and_conditions', 'pages_setup'),
            'refund_policy' => business_config('refund_policy', 'pages_setup'),
            'return_policy' => business_config('return_policy', 'pages_setup'),
            'cancellation_policy' => business_config('cancellation_policy', 'pages_setup'),
            'privacy_policy' => business_config('privacy_policy', 'pages_setup'),
        ]), 200);
    }

    public function get_zone(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $point = new Point($request->lat, $request->lng);
        $zone = Zone::contains('coordinates', $point)->ofStatus(1)->latest()->first();

        if ($zone) {
            return response()->json(response_formatter(DEFAULT_200, $zone), 200);
        }

        return response()->json(response_formatter(ZONE_RESOURCE_404), 200);
    }

    public function place_api_autocomplete(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'search_text' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $response = Http::get($this->google_map_base_api . '/place/autocomplete/json?input=' . $request['search_text'] . '&key=' . $this->google_map->live_values['map_api_key_server']);
        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function distance_api(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'origin_lat' => 'required',
            'origin_lng' => 'required',
            'destination_lat' => 'required',
            'destination_lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $request['origin_lat'] . ',' . $request['origin_lng'] . '&destinations=' . $request['destination_lat'] . ',' . $request['destination_lng'] . '&key=' . $this->google_map->live_values['map_api_key_server']);

        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function place_api_details(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'placeid' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $response = Http::get('https://maps.googleapis.com/maps/api/place/details/json?placeid=' . $request['placeid'] . '&key=' . $this->google_map->live_values['map_api_key_server']);

        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    public function geocode_api(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lng' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $response = Http::get('https://maps.googleapis.com/maps/api/geocode/json?latlng=' . $request->lat . ',' . $request->lng . '&key=' . $this->google_map->live_values['map_api_key_server']);
        return response()->json(response_formatter(DEFAULT_200, $response->json()), 200);
    }

    private function getPaymentMethods(): array
    {
        // Check if the addon_settings table exists
        if (!Schema::hasTable('addon_settings')) {
            return [];
        }

        $methods = DB::table('addon_settings')->where('settings_type', 'payment_config')->get();
        $env = env('APP_ENV') == 'live' ? 'live' : 'test';
        $credentials = $env . '_values';

        $data = [];
        foreach ($methods as $method) {
            $credentialsData = json_decode($method->$credentials);
            $additional_data = json_decode($method->additional_data);
            if ($credentialsData->status == 1) {
                $data[] = [
                    'gateway' => $method->key_name,
                    'gateway_image' => $additional_data?->gateway_image
                ];
            }
        }
        return $data;
    }

}
