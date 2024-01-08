<?php

namespace Modules\PromotionManagement\Http\Controllers\Api\V1\Customer;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\CartModule\Entities\Cart;
use Modules\PromotionManagement\Entities\Coupon;
use Modules\PromotionManagement\Entities\CouponCustomer;
use Modules\PromotionManagement\Entities\Discount;
use Modules\PromotionManagement\Entities\DiscountType;
use Modules\ServiceManagement\Entities\Service;

class CouponController extends Controller
{
    protected $discount, $coupon, $discountType, $cart, $service, $coupon_customer, $booking;
    private bool $is_customer_logged_in;
    private mixed $customer_user_id;

    public function __construct(Coupon $coupon, CouponCustomer $coupon_customer, Discount $discount, DiscountType $discountType, Cart $cart, Service $service, Booking $booking, Request $request)
    {
        $this->discount = $discount;
        $this->discountQuery = $discount->ofPromotionTypes('coupon');
        $this->coupon = $coupon;
        $this->coupon_customer = $coupon_customer;
        $this->discountType = $discountType;
        $this->cart = $cart;
        $this->service = $service;
        $this->booking = $booking;

        $this->is_customer_logged_in = (bool)auth('api')->user();
        $this->customer_user_id = $this->is_customer_logged_in ? auth('api')->user()->id : $request['guest_id'];
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $active_coupons = $this->coupon->with(['discount'])
            ->when(!is_null($request->status), function ($query) use ($request) {
                $query->ofStatus(1);
            })
            ->whereHas('discount', function ($query) {
                $query->where(['promotion_type' => 'coupon'])
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->where('is_active', 1);
            })
            ->whereHas('discount.discount_types', function ($query) {
                $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
            })
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        $expired_coupons = $this->coupon->with(['discount'])
            ->when(!is_null($request->status), function ($query) use ($request) {
                $query->ofStatus(1);
            })
            ->whereHas('discount', function ($query) {
                $query->where(['promotion_type' => 'coupon'])
                    ->whereDate('end_date', '<', now())
                    ->where('is_active', 1);
            })
            ->whereHas('discount.discount_types', function ($query) {
                $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
            })
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, ['active_coupons' => $active_coupons, 'expired_coupons' => $expired_coupons]), 200);
    }

    /**
     * Show the form for creating a new resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function apply_coupon(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'coupon_code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $cart_items = $this->cart->where(['customer_id' => $this->customer_user_id])->get();
        $type_wise_id = [];
        foreach ($cart_items as $item) {
            $type_wise_id[] = $item['service_id'];
            $type_wise_id[] = $item['category_id'];
        }

        //find valid coupons
        $coupon = $this->coupon->where(['coupon_code' => $request['coupon_code']])
            ->whereHas('discount', function ($query) {
                $query->where(['promotion_type' => 'coupon'])
                    ->whereDate('start_date', '<=', now())
                    ->whereDate('end_date', '>=', now())
                    ->where('is_active', 1);
            })->whereHas('discount.discount_types', function ($query) {
                $query->where(['discount_type' => 'zone', 'type_wise_id' => config('zone_id')]);
            })->with('discount.discount_types', function ($query) use ($type_wise_id) {
                $query->whereIn('type_wise_id', array_unique($type_wise_id));
            })->latest()->first();

        $discounted_ids = [];
        if (isset($coupon) && isset($coupon->discount) && $coupon->discount->discount_types->count() > 0) {
            $discounted_ids = $coupon->discount->discount_types->pluck('type_wise_id')->toArray();
        }

        if (!isset($coupon)) {
            return response()->json(response_formatter(DEFAULT_404), 200);
        }

        //coupon type check
        if ($coupon->coupon_type == 'first_booking') {
            $bookings = $this->booking->where('customer_id', $this->customer_user_id)->count();
            if ($bookings > 1) {
                return response()->json(response_formatter(COUPON_NOT_VALID_FOR_CART), 200);
            }
        } else if ($coupon->coupon_type == 'customer_wise') {
            $coupon_customer = $this->coupon_customer
                ->where('coupon_id', $coupon->id)
                ->where('customer_user_id', $this->customer_user_id)
                ->exists();
            if (!$coupon_customer) {
                return response()->json(response_formatter(COUPON_NOT_VALID_FOR_CART), 200);
            }
        }

        //coupon limit check
        $limit = $this->booking
            ->where('customer_id', $this->customer_user_id)
            ->where('coupon_code', $coupon['coupon_code'])
            ->count();

        if ($coupon->coupon_type != 'first_booking' && $limit >= $coupon->discount->limit_per_user) {
            return response()->json(response_formatter(COUPON_NOT_VALID_FOR_CART), 200);
        }

        if ($coupon->coupon_type == 'first_booking') {
            $limit = $this->booking
                ->where('customer_id', $this->customer_user_id)
                ->where('booking_status', '!=', 'canceled')
                ->count();
            if ($limit >= $coupon->discount->limit_per_user) {
                return response()->json(response_formatter(COUPON_NOT_VALID_FOR_CART), 200);
            }
        }

        //apply
        $applied = 0;
        foreach ($cart_items as $item) {
            if (in_array($item->service_id, $discounted_ids) || in_array($item->category_id, $discounted_ids)) {
                $cart_item = $this->cart->where('id', $item['id'])->first();
                $service = $this->service->find($cart_item['service_id']);

                //calculation
                $coupon_discount_amount = booking_discount_calculator($coupon->discount, $cart_item->service_cost * $cart_item['quantity']);
                $basic_discount = $cart_item->discount_amount;
                $campaign_discount = $cart_item->campaign_discount;
                $subtotal = round($cart_item->service_cost * $cart_item['quantity'], 2);
                $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
                $tax = round(((($cart_item->service_cost - $applicable_discount - $coupon_discount_amount) * $service['tax']) / 100) * $cart_item['quantity'], 2);

                //update carts table
                $cart_item->coupon_discount = $coupon_discount_amount;
                $cart_item->coupon_code = $coupon->coupon_code;
                $cart_item->tax_amount = $tax;
                $cart_item->total_cost = round($subtotal - $applicable_discount - $coupon_discount_amount + $tax, 2);
                $cart_item->save();
                $applied = 1;
            }
        }

        if ($applied) {
            return response()->json(response_formatter(DEFAULT_200), 200);
        }
        return response()->json(response_formatter(COUPON_NOT_VALID_FOR_CART), 200);

    }

    /**
     * Show the form for creating a new resource.
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_coupon(Request $request): JsonResponse
    {
        $cart_items = $this->cart->where('customer_id', $this->customer_user_id)->get();
        if (!isset($cart_items)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        foreach ($cart_items as $cart) {
            $service = $this->service->find($cart['service_id']);

            $basic_discount = $cart->discount_amount;
            $campaign_discount = $cart->campaign_discount;
            $subtotal = round($cart->service_cost * $cart['quantity'], 2);
            $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;
            $tax = round(((($cart->service_cost - $applicable_discount) * $service['tax']) / 100) * $cart['quantity'], 2);

            //updated values
            $cart->tax_amount = $tax;
            $cart->total_cost = round($subtotal - $applicable_discount + $tax, 2);
            $cart->coupon_discount = 0;
            $cart->coupon_code = null;
            $cart->save();
        }

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

}
