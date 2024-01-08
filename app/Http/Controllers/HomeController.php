<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\CartModule\Entities\Cart;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\BookingModule\Entities\Booking;
use Modules\BookingModule\Http\Traits\BookingTrait;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Modules\ZoneManagement\Entities\Zone;
use App\Models\Blog;

class HomeController extends Controller
{
    use BookingTrait;
    private UserAddress $address;

    public function __construct(UserAddress $address)
    {
        $this->address = $address;
    }

    public function profile()
    {
        $bookings = Booking::where('customer_id', Auth::user()->id)->get();
        $user = User::where('id', Auth::user()->id)->first();
        return view('frontend.profile', compact('user', 'bookings'));
    }

    public function updateprofile(Request $request)
    {
        $user = User::find(Auth::user()->id);
        if ($request->hasFile('profile_image')) {
            $uploadedFile = $request->file('profile_image');
            $filename = $uploadedFile->getClientOriginalName();
            $publicDirectory = public_path('assets/landing/landing/images/profileimages');
            $uploadedFile->move($publicDirectory, $filename);
            $filePath = 'assets/landing/landing/images/profileimages/' . $filename;
            $user->profile_image = $filePath;
        }
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->update();
        session()->forget('notify');
        $notify[] = ['success', 'Profile Updated Successfully'];
        return redirect()->route('profile')->withNotify($notify);
    }

    public function customer_logout()
    {
        Auth::logout();
        return redirect()->route('home');
    }

    public function checkout()
    {
        if (Auth::check()) {
            $carts = Cart::with(['customer', 'provider.owner', 'category', 'sub_category', 'service', 'variation'])
                ->where(['customer_id' => Auth::user()->id])
                ->latest()->get();
            return view('frontend.checkout', compact('carts'));
        } else {
            $carts = [];
            return view('frontend.checkout', compact('carts'));
        }
    }

    public function place_request(Request $request)
    {
        $request->validate([
            'lat' => 'required',
            'lon' => 'required',
            'city' => '',
            'street' => '',
            'zip_code' => '',
            'country' => '',
            'address' => 'required',
            'address_type' => 'required|in:service,billing',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'house' => 'nullable',
            'floor' => 'nullable',
            'service_schedule' => 'required|date',
        ]);

        $customer_user_id = Auth::user()->id;

        $point = new Point($request->lat, $request->lon);
        $zone_id = Zone::contains('coordinates', $point)->ofStatus(1)->latest()->first()?->id;

        $address = $this->address;
        $address->user_id = $customer_user_id;
        $address->lat = $request->lat;
        $address->lon = $request->lon;
        $address->city = $request->city;
        $address->street = $request->street ?? '';
        $address->zip_code = $request->zip_code;
        $address->country = $request->country;
        $address->address = $request->address;
        $address->zone_id = $zone_id;
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->contact_person_number = $request->contact_person_number;
        $address->house = $request->house;
        $address->floor = $request->floor;
        $address->save();
        $response = $this->place_booking_request($customer_user_id, $request, 'cash-payment', 0);
        if ($response['flag'] == 'success') {
            $notify[] = ['success', 'Order Placed Successfully'];
            return redirect()->route('home')->withNotify($notify);
        } else {
            return redirect()->back()->withErrors(['error' => 'Something went wrong']);
        }
        $notify[] = ['success', 'Order Placed Successfully'];
        return redirect()->route('home')->withNotify($notify);
    }

    public function blogs()
    {
        $blogs = Blog::all();
        return view('frontend.blogs', compact('blogs'));
    }

    public function blog_detail($id)
    {
        $blog = Blog::find($id);
        return view('frontend.blog-detail', compact('blog'));
    }
}
