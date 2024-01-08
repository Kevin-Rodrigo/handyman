<?php

namespace Modules\CartModule\Traits;

use Modules\CartModule\Entities\AddedToCart;

trait AddedToCartTrait
{
    protected function added_to_cart_update($user_id, $service_id, $is_guest): void
    {
        $added_to_cart = AddedToCart::where(['user_id' => $user_id, 'service_id' => $service_id])->first();

        if (!isset($added_to_cart)) {
            $added_to_cart = new AddedToCart();
            $added_to_cart->user_id = $user_id;
            $added_to_cart->service_id = $service_id;
            $added_to_cart->count = 1;
            $added_to_cart->is_guest = $is_guest;
            $added_to_cart->save();
        } else {
            $added_to_cart->increment('count');
        }
    }
}
