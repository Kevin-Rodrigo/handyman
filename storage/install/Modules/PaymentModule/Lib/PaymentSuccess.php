<?php

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Modules\CartModule\Entities\Cart;
use Modules\CartModule\Entities\CartServiceInfo;
use Modules\PaymentModule\Lib\PaymentResponse;
use Modules\PaymentModule\Traits\PaymentHelperTrait;
use Modules\UserManagement\Entities\User;

if (!function_exists('digital_payment_success')) {
    /**
     * @param $data
     * @return void
     */
    function digital_payment_success($data): void
    {
        PaymentResponse::success($data);
    }
}

if (!function_exists('digital_payment_fail')) {
    /**
     * @param $data
     * @return void
     */
    function digital_payment_fail($data): void
    {
        //
    }
}

