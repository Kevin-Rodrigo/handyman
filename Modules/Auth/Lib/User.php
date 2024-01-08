<?php


use Modules\ProviderManagement\Entities\Provider;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;

if (!function_exists('get_user_id')) {
    function get_user_id($id, $user_type)
    {
        if(in_array($user_type, ADMIN_USER_TYPES)) {
            $user_id = User::find($id)->id;
        }
        elseif(in_array($user_type, CUSTOMER_USER_TYPES)) {
            $user_id = User::find($id)->id;
        }
        elseif(in_array($user_type, PROVIDER_USER_TYPES)) {
            $user_id = Provider::find($id)->user_id;
        }
        elseif(in_array($user_type, [SERVICEMAN_USER_TYPES])) {
            $user_id = Serviceman::find($id)->user_id;
        }

        return $user_id;
    }
}
