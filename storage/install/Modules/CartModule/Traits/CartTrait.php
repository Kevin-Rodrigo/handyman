<?php

namespace Modules\CartModule\Traits;

use Modules\CartModule\Entities\Cart;
use Modules\ServiceManagement\Entities\Service;

trait CartTrait
{
    /**
     * @param $cart_id
     * @param $quantity
     * @return bool
     */
    public function update_cart_quantity($cart_id, $quantity): bool
    {
        $cart = Cart::find($cart_id);
        $service = Service::with(['service_discount', 'campaign_discount'])->find($cart['service_id']);

        if (!isset($cart) || !isset($service)) return false;

        $basic_discount = basic_discount_calculation($service, $cart->service_cost * $quantity);
        $campaign_discount = campaign_discount_calculation($service, $cart->service_cost * $quantity);
        $subtotal = round($cart->service_cost * $quantity, 2);

        $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
        $tax = round(((($cart->service_cost - $applicable_discount) * $service['tax']) / 100) * $quantity, 2);

        //between normal discount & campaign discount, greater one will be calculated
        $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
        $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

        $cart->quantity = $quantity;
        $cart->discount_amount = $basic_discount;
        $cart->campaign_discount = $campaign_discount;
        $cart->coupon_discount = 0;
        $cart->coupon_code = null;
        $cart->tax_amount = round($tax, 2);
        $cart->total_cost = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);
        $cart->save();

        return true;
    }

}
