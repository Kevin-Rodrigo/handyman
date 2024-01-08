<?php

use Illuminate\Support\Str;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;
use Modules\ProviderManagement\Entities\ProviderSetting;
use Modules\UserManagement\Entities\User;

if (!function_exists('business_config')) {
    function business_config($key, $settings_type)
    {
        try {
            $config = BusinessSettings::where('key_name', $key)->where('settings_type', $settings_type)->first();
        } catch (Exception $exception) {
            return null;
        }

        return (isset($config)) ? $config : null;
    }
}

if (!function_exists('provider_config')) {
    function provider_config($key, $settings_type, $provider_id)
    {
        try {
            $config = ProviderSetting::where('key_name', $key)->where('settings_type', $settings_type)->first();
        } catch (Exception $exception) {
            return null;
        }

        return (isset($config)) ? $config : null;
    }
}


if (!function_exists('pagination_limit')) {
    function pagination_limit()
    {
        try {
            if (!session()->has('pagination_limit')) {
                $limit = BusinessSettings::where('key_name', 'pagination_limit')->where('settings_type', 'business_information')->first()->live_values;
                session()->put('pagination_limit', $limit);
            } else {
                $limit = session('pagination_limit');
            }
        } catch (Exception $exception) {
            return 10;
        }

        return $limit;
    }
}

if (!function_exists('currency_code')) {
    function currency_code(): string
    {
        $code = business_config('currency_code', 'business_information')['live_values'];
        return $code ?? 'USD';
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol(): string
    {
        $code = business_config('currency_code', 'business_information')['live_values'];
        $symbol = '$';
        foreach (CURRENCIES as $currency) {
            if ($currency['code'] == $code) {
                $symbol = $currency['symbol'];
            }
        }

        return $symbol;
    }
}

if (!function_exists('with_currency_symbol')) {
    function with_currency_symbol($value): string
    {
        $position = business_config('currency_symbol_position', 'business_information')['live_values']??'right';
        $decimal_point = business_config('currency_decimal_point', 'business_information')['live_values']??2;
        $code = business_config('currency_code', 'business_information')['live_values'];
        $symbol = '$';
        foreach (CURRENCIES as $currency) {
            if ($currency['code'] == $code) {
                $symbol = $currency['symbol'];
            }
        }

        if($position == 'left') {
            return $symbol . number_format($value, $decimal_point, '.', '');
        } else {
            return number_format($value, $decimal_point, '.', '') . $symbol;
        }

    }
}

if (!function_exists('with_decimal_point')) {
    function with_decimal_point($value): float
    {
        $decimal_point = business_config('currency_decimal_point', 'business_information')['live_values']??2;
        return (float)(number_format($value, $decimal_point, '.', ''));
    }
}

if (!function_exists('generate_referer_code')) {
    function generate_referer_code() {
        $ref_code = strtoupper(Str::random(10));

        if (User::where('ref_code', '=', $ref_code)->exists()) {
            return generate_referer_code();
        }

        return $ref_code;
    }
}

