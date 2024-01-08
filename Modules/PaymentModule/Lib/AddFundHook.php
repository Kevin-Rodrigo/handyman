<?php

use Modules\UserManagement\Entities\User;

if (!function_exists('add_fund_success')) {
    /**
     * @param $data
     * @return void
     */
    function add_fund_success($data): void
    {
        $customer_user_id = $data['payer_id'];
        $amount = $data['payment_amount'];
        add_fund_transactions($customer_user_id, $amount);

        //send notification
        $customer_fcm = User::find($customer_user_id)->fcm_token;
        $title = with_currency_symbol($amount) . ' ' . translate('has been credited to your wallet');
        if ($customer_fcm) {
            device_notification($customer_fcm, $title, null, null, null, NOTIFICATION_TYPE['wallet'], null, $customer_user_id);
        }
    }
}

if (!function_exists('add_fund_fail')) {
    /**
     * @param $data
     * @return void
     */
    function add_fund_fail($data): void
    {
        //
    }
}
