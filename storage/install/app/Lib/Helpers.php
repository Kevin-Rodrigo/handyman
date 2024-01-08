<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Response;
use Modules\PaymentModule\Entities\Bonus;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

if (!function_exists('translate')) {
    function translate($key)
    {
        try {
            App::setLocale('en');
            $lang_array = include(base_path('resources/lang/' . 'en' . '/lang.php'));
            $processed_key = ucfirst(str_replace('_', ' ', str_ireplace(['\'', '"', ',', ';', '<', '>', '?'], ' ', $key)));
            if (!array_key_exists($key, $lang_array)) {
                $lang_array[$key] = $processed_key;
                $str = "<?php return " . var_export($lang_array, true) . ";";
                file_put_contents(base_path('resources/lang/' . 'en' . '/lang.php'), $str);
                $result = $processed_key;
            } else {
                $result = __('lang.' . $key);
            }
            return $result;
        } catch (\Exception $exception) {
            return $key;
        }
    }
}

if (!function_exists('bs_data')) {
    function bs_data($settings, $key, $required = 0)
    {
        try {
            if (env('APP_ENV') == 'local' || env('APP_ENV') == 'live' || $required) {
                $config = $settings->where('key_name', $key)->first()->live_values;
            } else {
                $config = null;
            }

        } catch (Exception $exception) {
            return null;
        }

        return (isset($config)) ? $config : null;
    }
}

if (!function_exists('error_processor')) {
    function error_processor($validator)
    {
        $errors = [];
        foreach ($validator->errors()->getMessages() as $index => $error) {
            $errors[] = ['error_code' => $index, 'message' => translate($error[0])];
        }
        return $errors;
    }
}

if (!function_exists('get_path')) {
    function get_path($type)
    {
        if ($type == 'public') {
            return url('/') . '/public';
        }

        return url('/');
    }
}

if (!function_exists('response_formatter')) {
    function response_formatter($constant, $content = null, $errors = []): array
    {
        $constant = (array)$constant;
        $constant['content'] = $content;
        $constant['errors'] = $errors;
        return $constant;
    }
}

if (!function_exists('file_uploader')) {
    function file_uploader(string $dir, string $format, $image = null, $old_image = null)
    {
        if ($image == null) return $old_image ?? 'def.png';

        if (isset($old_image)) Storage::disk('public')->delete($dir . $old_image);

        $imageName = \Carbon\Carbon::now()->toDateString() . "-" . uniqid() . "." . $format;
        if (!Storage::disk('public')->exists($dir)) {
            Storage::disk('public')->makeDirectory($dir);
        }
        Storage::disk('public')->put($dir . $imageName, file_get_contents($image));

        return $imageName;
    }
}

if (!function_exists('file_remover')) {
    function file_remover(string $dir, $image)
    {
        if (!isset($image)) return true;

        if (Storage::disk('public')->exists($dir . $image)) Storage::disk('public')->delete($dir . $image);

        return true;
    }
}

if (!function_exists('divnum')) {
    function divnum($numerator, $denominator)
    {
        return $denominator == 0 ? 0 : ($numerator / $denominator);
    }
}

if (!function_exists('access_checker')) {
    function access_checker($module)
    {
        if (auth()->user()->user_type == 'super-admin') {
            return true;
        } elseif (auth()->user()->roles->count() > 0) {
            $modules = auth()->user()->roles[0]->modules;
            if (in_array($module, $modules)) {
                return true;
            } else {
                return false;
            }
        }
    }
}

if (!function_exists('exc_handler')) {
    function exc_handler($data)
    {
        try {
            $response = $data;
        } catch (Exception $exception) {
            $response = translate('not_available');
        }
        return $response;
    }
}


if (!function_exists('get_routes')) {
    function get_routes($for_user)
    {
        $routes = Route::getRoutes()->getRoutesByMethod();
        $results = array();
        $skip = ['{id}', 'ajax', 'login', 'logout', 'download', 'check', 'set', '-get', 'chat', 'update'];
        $replace_from = [''];
        $replace_to = [''];
        foreach ($routes['GET'] as $route) {
            $path = $route->uri();
            $readable = preg_replace('/\/\{(one|two|three|four|five)\?\}/', '', $path);
            $len = strlen($for_user);
            if ((substr($readable, 0, $len) === $for_user)) {
                if (strposa($readable, $skip, 1) == false) {
                    $results[] = str_replace($replace_from, $replace_to, $readable);
                }
            }
        }
        sort($results);

        return $results;
    }

    function strposa($haystack, $needles = array(), $offset = 0)
    {
        $chr = array();
        foreach ($needles as $needle) {
            $res = strpos($haystack, $needle, $offset);
            if ($res !== false) $chr[$needle] = $res;
        }
        if (empty($chr)) return false;
        return min($chr);
    }
}

if (!function_exists('get_add_money_bonus')) {
    function get_add_money_bonus($amount)
    {
        $bonuses = Bonus::where('is_active', 1)
        ->whereDate('start_date', '<=', now())
        ->whereDate('end_date', '>=', now())
        ->where('minimum_add_amount', '<=', $amount)
        ->get();

        $bonuses = $bonuses->where('minimum_add_amount', $bonuses->max('minimum_add_amount'));

        foreach ($bonuses as $key=>$item) {
            $item->applied_bonus_amount = $item->bonus_amount_type == 'percent' ? ($amount*$item->bonus_amount)/100 : $item->bonus_amount;

            //max bonus check
            if($item->bonus_amount_type == 'percent' && $item->applied_bonus_amount > $item->maximum_bonus_amount) {
                $item->applied_bonus_amount = $item->maximum_bonus_amount;
            }
        }

        return $bonuses->max('applied_bonus_amount') ?? 0;
    }
}

if (!function_exists('get_distance')) {
    function get_distance(array $originCoordinates,array $destinationCoordinates, $unit = 'K'): float
    {
        $lat1 = (float) $originCoordinates[0];
        $lat2 = (float) $destinationCoordinates[0];
        $lon1 = (float) $originCoordinates[1];
        $lon2 = (float) $destinationCoordinates[1];

        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0;
        } else {
            $theta = $lon1 - $lon2;
            $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +  cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
            $dist = acos($dist);
            $dist = rad2deg($dist);
            $miles = $dist * 60 * 1.1515;
            $unit = strtoupper($unit);
            if ($unit == "K") {
                return ($miles * 1.609344);
            } else if ($unit == "N") {
                return ($miles * 0.8684);
            } else {
                return $miles;
            }
        }
    }
}
