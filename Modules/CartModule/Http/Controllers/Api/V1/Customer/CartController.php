<?php

namespace Modules\CartModule\Http\Controllers\Api\V1\Customer;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\CartModule\Entities\Cart;
use Modules\CartModule\Entities\CartServiceInfo;
use Modules\CartModule\Traits\AddedToCartTrait;
use Modules\CartModule\Traits\CartTrait;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Service;
use Modules\ServiceManagement\Entities\Variation;
use Modules\UserManagement\Entities\Guest;
use Modules\UserManagement\Entities\User;
use Ramsey\Uuid\Uuid;

class CartController extends Controller
{
    private Cart $cart;
    private Service $service;
    private Variation $variation;
    private User $user;
    private Provider $provider;
    private Guest $guest;
    private bool $is_customer_logged_in;
    private mixed $customer_user_id;

    use CartTrait, AddedToCartTrait;

    public function __construct(Cart $cart, Service $service, Variation $variation, User $user, Provider $provider, Guest $guest, Request $request)
    {
        $this->cart = $cart;
        $this->service = $service;
        $this->variation = $variation;
        $this->user = $user;
        $this->provider = $provider;
        $this->guest = $guest;

        $this->is_customer_logged_in = (bool)auth('api')->user();
        $this->customer_user_id = $this->is_customer_logged_in ? auth('api')->user()->id : $request['guest_id'];
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function add_to_cart(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'uuid',
            'service_id' => 'required|uuid',
            'category_id' => 'required|uuid',
            'sub_category_id' => 'required|uuid',
            'variant_key' => 'required',
            'quantity' => 'required|numeric|min:1|max:1000',
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $customer_user_id = $this->customer_user_id;

        //cart log (for service)
        $this->added_to_cart_update($customer_user_id, $request['service_id'], !$this->is_customer_logged_in);

        $variation = $this->variation
            ->where(['zone_id' => Config::get('zone_id'), 'service_id' => $request['service_id']])
            ->where(['variant_key' => $request['variant_key']])
            ->first();

        if (isset($variation)) {
            $service = $this->service->find($request['service_id']);

            $check_cart = $this->cart->where([
                'service_id' => $request['service_id'],
                'variant_key' => $request['variant_key'],
                'customer_id' => $customer_user_id])->first();
            $cart = $check_cart ?? $this->cart;
            $quantity = $request['quantity'];

            //calculation
            $basic_discount = basic_discount_calculation($service, $variation->price * $quantity);
            $campaign_discount = campaign_discount_calculation($service, $variation->price * $quantity);
            $subtotal = round($variation->price * $quantity, 2);

            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;

            $tax = round((($variation->price * $quantity - $applicable_discount) * $service['tax']) / 100, 2);

            //between normal discount & campaign discount, greater one will be calculated
            $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
            $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

            //DB part
            $cart->provider_id = $request['provider_id'];
            $cart->customer_id = $customer_user_id;
            $cart->service_id = $request['service_id'];
            $cart->category_id = $request['category_id'];
            $cart->sub_category_id = $request['sub_category_id'];
            $cart->variant_key = $request['variant_key'];
            $cart->quantity = $quantity;
            $cart->service_cost = $variation->price;
            $cart->discount_amount = $basic_discount;
            $cart->campaign_discount = $campaign_discount;
            $cart->coupon_discount = 0;
            $cart->coupon_code = null;
            $cart->is_guest = !$this->is_customer_logged_in;
            $cart->tax_amount = round($tax, 2);
            $cart->total_cost = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);
            $cart->save();

            if (!$this->is_customer_logged_in) {
                $guest = $this->guest;
                $guest->ip_address = $request->ip();
                $guest->guest_id = $request->guest_id;
                $guest->save();
            }

            return response()->json(response_formatter(DEFAULT_STORE_200), 200);
        }

        return response()->json(response_formatter(DEFAULT_404), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $customer_user_id = $this->customer_user_id;
        $cart = $this->cart
            ->with(['customer', 'provider.owner', 'category', 'sub_category', 'service'])
            ->where(['customer_id' => $customer_user_id])
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])
            ->withPath('');

        $wallet_balance = $this->user->find($customer_user_id)?->wallet_balance ?? 0;

        $additional_charge = 0;
        if ((business_config('booking_additional_charge', 'booking_setup'))?->live_values) {
            $additional_charge = (business_config('additional_charge_fee_amount', 'booking_setup'))?->live_values;
        }
        $total_cost = $cart->sum('total_cost') + $additional_charge;

        return response()->json(response_formatter(DEFAULT_200, ['total_cost' => $total_cost, 'wallet_balance' => with_decimal_point($wallet_balance), 'cart' => $cart]), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update_qty(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
            'quantity' => 'required|numeric|min:1|max:1000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //update cart
        $this->update_cart_quantity($id, $request['quantity']);

        //returning current cart data
        $customer_user_id = $this->customer_user_id;
        $cart = $this->cart
            ->with(['customer', 'provider.owner', 'category', 'sub_category', 'service'])
            ->where(['customer_id' => $customer_user_id])
            ->latest()
            ->paginate(100, ['*'], 'offset', 1)
            ->withPath('');

        $additional_charge = 0;
        if ((business_config('booking_additional_charge', 'booking_setup'))?->live_values) {
            $additional_charge = (business_config('additional_charge_fee_amount', 'booking_setup'))?->live_values;
        }
        $total_cost = $cart->sum('total_cost') + $additional_charge;

        $wallet_balance = $this->user->find($customer_user_id)?->wallet_balance ?? 0;

        return response()->json(response_formatter(DEFAULT_UPDATE_200, ['total_cost' => $total_cost, 'wallet_balance' => with_decimal_point($wallet_balance), 'cart' => $cart]), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function update_provider(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|uuid'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        //check if provider exists
        if (!$this->provider->where('id', $request['provider_id'])->exists()) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        //update provider
        $this->cart
            ->where('customer_id', $this->customer_user_id)
            ->update(['provider_id' => $request['provider_id']]);

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function remove(Request $request, string $id): JsonResponse
    {
        $cart = $this->cart->where(['id' => $id])->first();

        if (!isset($cart)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        $this->cart->where('id', $id)->delete();

        return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function empty_cart(Request $request): JsonResponse
    {
        $cart = $this->cart->where(['customer_id' => $this->customer_user_id]);
        if ($cart->count() == 0) return response()->json(response_formatter(DEFAULT_204), 200);
        $cart->delete();

        return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
    }
}
