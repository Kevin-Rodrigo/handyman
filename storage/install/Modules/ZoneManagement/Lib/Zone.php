<?php


use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;

if (!function_exists('format_coordiantes')) {
    function format_coordinates($object): string
    {
        $str = '';
        foreach ($object as $key => $coordinate) {
            $str .= '(' . $coordinate->lat . ', ' . $coordinate->lng . ')' . ($key + 1 != count($object) ? ',' : '');
        }
        return $str;
    }
}
