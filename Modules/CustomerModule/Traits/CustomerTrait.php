<?php

namespace Modules\CustomerModule\Traits;

use Modules\CartModule\Entities\Cart;
use Modules\CartModule\Traits\CartTrait;
use Modules\UserManagement\Entities\UserAddress;
use Illuminate\Support\Facades\DB;

trait CustomerTrait
{
    use CartTrait;
    /**
     * @param $customer_user_id
     * @param $guest_id
     * @return void
     */
    public function update_address_and_cart_user($customer_user_id, $guest_id): void
    {
        DB::transaction(function () use ($customer_user_id, $guest_id) {
            $logged_user_carts = Cart::where('customer_id', $customer_user_id)->get();
            $guest_carts = Cart::where('customer_id', $guest_id)->get();

            if (count($logged_user_carts) > 0 && count($guest_carts) >0) {
                $guest_cart_sub_category_id = Cart::where('customer_id', $guest_id)->first()?->sub_category_id;
                foreach ($logged_user_carts as $cart) {
                    $guest_cart = $guest_carts->where('variant_key', $cart->variant_key)->first();

                    if ($cart->sub_category_id == $guest_cart_sub_category_id) {
                        $quantity = $cart->quantity + $guest_cart?->quantity ?? 0;
                        $this->update_cart_quantity($cart->id, $quantity);
                    }
                    Cart::where('variant_key', $cart->variant_key)->delete();
                }
            }

            Cart::where('customer_id', $guest_id)->update(['customer_id' => $customer_user_id]);
            UserAddress::where('user_id', $guest_id)->update(['user_id' => $customer_user_id]);
        });
    }

}
