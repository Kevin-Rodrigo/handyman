<?php

namespace Modules\CustomerModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\ReviewModule\Entities\Review;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CustomerController extends Controller
{
    protected User $user;
    private $booking;
    private $review;
    private $address;

    public function __construct(Booking $booking, User $user, Review $review, UserAddress $address)
    {
        $this->booking = $booking;
        $this->user = $user;
        $this->review = $review;
        $this->address = $address;
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): View|Factory|Application
    {
        return view('customermodule::admin.create');
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): View|Factory|Application
    {
        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['search' => $search, 'status' => $status];

        $customers = $this->user->withCount(['bookings'])->whereIn('user_type', CUSTOMER_USER_TYPES)
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%')
                            ->orWhere('email', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($status != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate(pagination_limit())->appends($query_param);

        return view('customermodule::admin.list', compact('customers', 'search', 'status'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users',
            'password' => 'required|min:6',
            'gender' => 'in:male,female,others',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
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
        $user->save();

        Toastr::success(REGISTRATION_200['message']);
        return back();
    }

    public function overview(Request $request, string $id): JsonResponse
    {
        $search = $request->has('search') ? $request['search'] : '';
        $web_page = $request->has('web_page') ? 'review' : 'general';
        $query_param = ['search' => $search, 'web_page' => $web_page];

        $customer = $this->user->where(['id' => $id])->with(['bookings', 'addresses', 'reviews'])->first();
        $total_booking_placed = $this->booking->where(['customer_id' => $id])->count();
        $total_booking_amount = $this->booking->where(['customer_id' => $id])->sum('total_booking_amount');
        $complete_bookings = $this->booking->where(['customer_id' => $id, 'booking_status' => 'completed'])->count();
        $canceled_bookings = $this->booking->where(['customer_id' => $id, 'booking_status' => 'canceled'])->count();
        $ongoing_bookings = $this->booking->where(['customer_id' => $id, 'booking_status' => 'ongoing'])->count();

        $data = [
            'total_booking_placed' => $total_booking_placed,
            'total_booking_amount' => $total_booking_amount,
            'complete_bookings' => $complete_bookings,
            'canceled_bookings' => $canceled_bookings,
            'ongoing_bookings' => $ongoing_bookings,
            'customer_details' => $customer
        ];

        return response()->json(response_formatter(DEFAULT_200, $data), 200);
    }

    public function bookings(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $bookings = $this->booking->with(['provider.owner'])->where(['customer_id' => $id])
            ->when($request->has('string'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', base64_decode($request['string']));
                    foreach ($keys as $key) {
                        $query->orWhere('readable_id', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->orderBy('created_at', 'desc')->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $bookings), 200);
    }

    public function reviews(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $reviews = $this->review->where(['customer_id' => $id])->orderBy('created_at', 'desc')->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $reviews), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return Application|Factory|View
     */
    public function edit(string $id): Application|Factory|View
    {
        $customer = $this->user->whereIn('user_type', CUSTOMER_USER_TYPES)->find($id);
        return view('customermodule::admin.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Application|Redirector|RedirectResponse
     */
    public function update(Request $request, string $id): Redirector|RedirectResponse|Application
    {
        $customer = $this->user->whereIn('user_type', CUSTOMER_USER_TYPES)->find($id);

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $customer->id,
            'phone' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10|unique:users,phone,' . $customer->id,
            'password' => '',
            'confirm_password' => !is_null($request->password) ? 'required|same:password' : '',
            'gender' => 'in:male,female,others',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        $customer->first_name = $request->first_name;
        $customer->last_name = $request->last_name;
        $customer->email = $request->email;
        $customer->phone = $request->phone;
        $customer->profile_image = $request->has('profile_image') ? file_uploader('user/profile_image/', 'png', $request->profile_image) : $customer->profile_image;
        $customer->date_of_birth = $request->date_of_birth;
        $customer->gender = $request->has('gender') ? $request->gender : $customer->gender;
        if (!is_null($request['password'])) {
            $customer->password = bcrypt($request->password);
        }
        $customer->save();

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return redirect('admin/customer/list');
    }


    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $user = $this->user->where('id', $id)->first();
        if (isset($user)) {
            file_remover('user/profile_image/', $user->profile_image);
            foreach ($user->identification_image as $image_name) {
                file_remover('user/identity/', $image_name);
            }
            $user->delete();

            Toastr::success(DEFAULT_DELETE_200['message']);
            return back();
        }
        Toastr::success(DEFAULT_204['message']);
        return back();
    }


    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function status_update(Request $request, $id): JsonResponse
    {
        $user = $this->user->where('id', $id)->first();
        $this->user->where('id', $id)->update(['is_active' => !$user->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store_address(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => '',
            'lon' => '',
            'city' => 'required',
            'street' => '',
            'zip_code' => 'required',
            'country' => 'required',
            'address' => 'required',
            'address_type' => 'required|in:service,billing',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address_label' => 'required',
            'customer_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $address = $this->address;
        $address->user_id = $request['customer_id'];
        $address->lat = $request->lat;
        $address->lon = $request->lon;
        $address->city = $request->city;
        $address->street = $request->street ?? '';
        $address->zip_code = $request->zip_code;
        $address->country = $request->country;
        $address->address = $request->address;
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->contact_person_number = $request->contact_person_number;
        $address->address_label = $request->address_label;
        $address->save();

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit_address(string $id): JsonResponse
    {
        $address = $this->address->where(['user_id' => $id])->where('id', $id)->first();
        if (isset($address)) {
            return response()->json(response_formatter(DEFAULT_200, $address), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update_address(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => '',
            'lon' => '',
            'city' => 'required',
            'street' => '',
            'zip_code' => 'required',
            'country' => 'required',
            'address' => 'required',
            'address_type' => 'required|in:service,billing',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address_label' => 'required',
            'customer_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $address = $this->address->where(['user_id' => $request['customer_id']])->where('id', $id)->first();
        if (!isset($address)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        $address->lat = $request->lat ?? "";
        $address->lon = $request->lon ?? "";
        $address->city = $request->city;
        $address->street = $request->has('street') ? $request->street : $address->street;
        $address->zip_code = $request->zip_code;
        $address->country = $request->country;
        $address->address = $request->address;
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->contact_person_number = $request->contact_person_number;
        $address->address_label = $request->address_label;
        $address->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy_address(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $address = $this->address->where(['user_id' => $request['customer_id']])->where('id', $id)->first();
        if (!isset($address)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }
        $address->delete();
        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->user->withCount(['bookings'])->whereIn('user_type', CUSTOMER_USER_TYPES)
            ->when($request->has('search'), function ($query) use ($request) {
                $keys = explode(' ', $request['search']);
                return $query->where(function ($query) use ($keys) {
                    foreach ($keys as $key) {
                        $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%')
                            ->orWhere('email', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->latest()->get();
        return (new FastExcel($items))->download(time().'-file.xlsx');
    }

    public function show($id, Request $request)
    {
        $request->validate([
            'web_page' => 'in:overview,bookings,reviews',
        ]);

        $web_page = $request->has('web_page') ? $request['web_page'] : 'overview';

        //overview
        if($request->web_page == 'overview') {
            $customer = $this->user->with(['account', 'addresses'])->withCount(['bookings'])->find($id);
            $total_booking_amount = $this->booking->where('customer_id', $id)->sum('total_booking_amount');

            $booking_overview = DB::table('bookings')->where('customer_id', $id)
                ->select('booking_status', DB::raw('count(*) as total'))
                ->groupBy('booking_status')
                ->get();

            $status = ['pending', 'accepted', 'ongoing', 'completed', 'canceled'];
            $total = [];
            foreach ($status as $item) {
                if ($booking_overview->where('booking_status', $item)->first() !== null) {
                    $total[] = $booking_overview->where('booking_status', $item)->first()->total;
                } else {
                    $total[] = 0;
                }
            }

            return view('customermodule::admin.detail.overview', compact('customer', 'total_booking_amount', 'web_page', 'total'));

        }
        //bookings
        elseif ($request->web_page == 'bookings') {

            $search = $request->has('search') ? $request['search'] : '';
            $query_param = ['web_page' => $web_page, 'search' => $search];

            $bookings = $this->booking->with(['provider.owner'])
                ->where('customer_id', $id)
                ->where(function ($query) use ($request) {
                    $keys = explode(' ', $request['search']);
                    foreach ($keys as $key) {
                        $query->where('readable_id', 'LIKE', '%' . $key . '%');
                    }
                })
                ->latest()
                ->paginate(pagination_limit())->appends($query_param);

            return view('customermodule::admin.detail.bookings', compact( 'bookings', 'web_page'));

        }
        //reviews
        elseif ($request->web_page == 'reviews') {
            $query_param = ['web_page' => $web_page];
            $booking_ids = $this->booking->where('customer_id', $id)->pluck('id')->toArray();
            $reviews = $this->review->with(['booking'])
                ->whereIn('booking_id', $booking_ids)
                ->latest()
                ->paginate(pagination_limit())->appends($query_param);
            return view('customermodule::admin.detail.reviews', compact('reviews','web_page'));

        }


    }

}
