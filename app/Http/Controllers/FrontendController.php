<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\CategoryManagement\Entities\Category;
use Modules\ServiceManagement\Entities\Service;
use Modules\UserManagement\Entities\User;
use Modules\CartModule\Entities\Cart;
use Modules\CartModule\Entities\CartServiceInfo;
use Modules\CartModule\Traits\AddedToCartTrait;
use Modules\CartModule\Traits\CartTrait;
use Modules\ProviderManagement\Entities\Provider;
use Modules\ServiceManagement\Entities\Variation;
use Illuminate\Support\Facades\Config;
use Ramsey\Uuid\Uuid;

class FrontendController extends Controller
{
    protected $user;
    private Cart $cart;
    private Service $service;
    private Variation $variation;
    private Provider $provider;

    use CartTrait, AddedToCartTrait;

    public function __construct(User $user, Cart $cart, Service $service, Variation $variation, Provider $provider)
    {
        $this->user = $user;
        $this->cart = $cart;
        $this->service = $service;
        $this->variation = $variation;
        $this->provider = $provider;
    }

    public function login()
    {
        return view('frontend.auth.login');
    }

    public function customer_login(Request $request)
    {
        $request->validate([
            'email_or_phone' => 'required'
        ]);


        $user = $this->user
            ->where('email', $request['email_or_phone'])
            ->ofType(CUSTOMER_USER_TYPES)
            ->first();

        //not found
        if (!isset($user)) {
            return redirect()->back()->withErrors(['error', 'User Not Found']);
        }

        //credentials mismatch
        if (!Hash::check($request['password'], $user['password'])) {
            return redirect()->back()->withErrors(['error', 'Invalid Credentials']);
        }

        //email verification
        // $email_verification = business_config('email_verification', 'service_setup')?->live_values ?? 0;
        // if ($email_verification && !$user->is_email_verified) {
        //     self::update_user_hit_count($user);
        //     return response()->json(response_formatter(UNVERIFIED_EMAIL), 401);
        // }

        //not active
        if (!$user->is_active) {
            return redirect()->back()->withErrors(['error', 'Not Active']);
        }
        Auth::login($user);
        //login success
        //$this->update_address_and_cart_user($user->id, $request['guest_id']);
        $notify[] = ['success', 'Logged In Successfully'];
        return redirect()->route('home')->withNotify($notify);
    }

    public function register()
    {
        return view('frontend.auth.register');
    }

    public function customer_register(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'profile_image' =>  'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        $user = $this->user;

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->profile_image = $request->has('profile_image') ? file_uploader('user/profile_image/', 'png', $request->profile_image) : 'default.png';
        $user->date_of_birth = $request->date_of_birth;
        $user->gender = $request->gender ?? 'male';
        $user->password = bcrypt($request->password);
        $user->user_type = 'customer';
        $user->is_active = 1;
        $user->referred_by = null;
        $user->save();
        Auth::login($user);
        $notify[] = ['success', 'Logged In Successfully'];
        return redirect()->route('home')->withNotify($notify);
    }

    public function index()
    {
        $categories = Category::where('is_active', '1')->get();
        $services = Service::with('category')->where('is_active', '1')->get();
        return view('frontend.home', compact('categories', 'services'));
    }

    public function categories()
    {
        $categories = Category::where('is_active', '1')->paginate(24);
        return view('frontend.categories', compact('categories'));
    }

    public function category_services($id)
    {
        $services = Service::where('category_id', $id)->where('is_active', '1')->paginate(32);
        return view('frontend.services', compact('services'));
    }

    public function services()
    {
        $services = Service::with('category')->where('is_active', '1')->paginate(24);
        return view('frontend.services', compact('services'));
    }

    public function service_detail($id)
    {
        $service = Service::with('category', 'variations', 'reviews', 'tags')->where('id', $id)->first();
        $relatedServices = Service::with('category')->where('category_id', $service->category->id)
            ->where('id', '!=', $service->id)
            ->take(3)
            ->get();
        return view('frontend.servicedetail', compact('service', 'relatedServices'));
    }

    public function cart()
    {
        if (Auth::check()) {
            $carts = Cart::with(['customer', 'provider.owner', 'category', 'sub_category', 'service', 'variation'])
                ->where(['customer_id' => Auth::user()->id])
                ->latest()->get();
            return view('frontend.cart', compact('carts'));
        } else {
            $carts = [];
            return view('frontend.cart', compact('carts'));
        }
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'serviceId' => 'required',
            'variations' => 'required'
        ], [
            'variations.required' => 'Please select a variation to proceed.'
        ]);
        if (!auth()->check()) {
            return response()->json(['error' => 'Please login first to add service to your cart.'], 401);
        }
        $this->added_to_cart_update(Auth::user()->id, $request->serviceId, 0);
        $counter = 0;
        $first = 0;
        $second = 0;
        foreach ($request->variations as $vari) {
            $variation = $this->variation
                ->where(['service_id' => $request->serviceId])
                ->where(['variant_key' => $vari['variationKey']])
                ->first();

            if (isset($variation)) {
                $service = $this->service->find($request->serviceId);

                $check_cart = $this->cart->where([
                    'service_id' => $request->serviceId,
                    'variant_key' => $vari['variationKey']
                ])->first();
                //$cart = $check_cart ?? $this->cart;
                $quantity = $vari['quantity'];

                //calculation
                $basic_discount = basic_discount_calculation($service, $variation->price * $quantity);
                $campaign_discount = campaign_discount_calculation($service, $variation->price * $quantity);
                $subtotal = round($variation->price * $quantity, 2);

                $applicable_discount = ($campaign_discount >= $basic_discount) ? $campaign_discount : $basic_discount;

                $tax = round((($variation->price * $quantity - $applicable_discount) * $service['tax']) / 100, 2);

                //between normal discount & campaign discount, greater one will be calculated
                $basic_discount = $basic_discount > $campaign_discount ? $basic_discount : 0;
                $campaign_discount = $campaign_discount >= $basic_discount ? $campaign_discount : 0;

                // Extract the first 36 characters to get the desired length
                $cart = new Cart();
                $cart->provider_id = $request['provider_id'];
                $cart->customer_id = Auth::user()->id;
                $cart->service_id = $request->serviceId;
                $cart->category_id = $service->category_id;
                $cart->sub_category_id = $service->sub_category_id;
                $cart->variant_key = $vari['variationKey'];
                $cart->quantity = $quantity;
                $cart->service_cost = $variation->price;
                $cart->discount_amount = $basic_discount;
                $cart->campaign_discount = $campaign_discount;
                $cart->coupon_discount = 0;
                $cart->coupon_code = null;
                $cart->tax_amount = round($tax, 2);
                $cart->total_cost = round($subtotal - $basic_discount - $campaign_discount + $tax, 2);
                $cart->save();
                $counter++;
                if ($counter == 1) {
                    $first = $cart->id;
                } else if ($counter == 2) {
                    $second = $cart->id;
                }
                //return response()->json(['message' => 'Added'],200);
            }
        }
        return response()->json(['message' => 'Added Successfully'], 200);
    }

    public function cart_remove($id)
    {
        $cart = $this->cart->where(['id' => $id])->first();
        $this->cart->where('id', $id)->delete();
        $notify[] = ['success', 'Removed successfully'];
        return redirect()->route('cart')->withNotify($notify);
    }
}
