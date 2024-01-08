<?php

namespace Modules\CustomerModule\Http\Controllers\Api\V1\Customer;

use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;

class AddressController extends Controller
{
    private UserAddress $address;
    private bool $is_customer_logged_in;
    private mixed $customer_user_id;

    public function __construct(UserAddress $address, Request $request)
    {
        $this->address = $address;

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
            'offset' => 'required|numeric|min:1|max:100000',
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        if ($this->is_customer_logged_in && !in_array(auth('api')->user()?->user_type, CUSTOMER_USER_TYPES)) {
            return response()->json(response_formatter(DEFAULT_403), 401);
        }

        $addresses = $this->address->where(['user_id' => $this->customer_user_id])
            ->latest()
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])
            ->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $addresses), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
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
            'address_label' => 'required',
            'house' => 'nullable',
            'floor' => 'nullable',
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $point = new Point($request->lat, $request->lon);
        $zone_id = Zone::contains('coordinates', $point)->ofStatus(1)->latest()->first()?->id;

        $address = $this->address;
        $address->user_id = $this->customer_user_id;
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
        $address->address_label = $request->address_label;
        $address->house = $request->house;
        $address->floor = $request->floor;
        $address->is_guest = !$this->is_customer_logged_in;
        $address->save();

        return response()->json(response_formatter(DEFAULT_STORE_200, $address), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @param Request $request
     * @return JsonResponse
     */
    public function edit(string $id, Request $request): JsonResponse
    {
        $address = $this->address->where(['user_id' => $this->customer_user_id])->where('id', $id)->first();
        if (!isset($address)) return response()->json(response_formatter(DEFAULT_404), 404);

        return response()->json(response_formatter(DEFAULT_200, $address), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'lat' => 'required',
            'lon' => 'required',
            'city' => 'nullable',
            'street' => 'nullable',
            'zip_code' => 'nullable',
            'country' => 'nullable',
            'address' => 'required',
            'address_type' => 'required|in:service,billing',
            'contact_person_name' => 'required',
            'contact_person_number' => 'required',
            'address_label' => 'required',
            'house' => 'nullable',
            'floor' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $point = new Point($request->lat, $request->lon);
        $zone = Zone::contains('coordinates', $point)->ofStatus(1)->latest()->first();
        if ($zone) {
            $zone_id = $zone->id;
        } else {
            $zone_id = null;
        }

        $address = $this->address->where(['user_id' => $this->customer_user_id])->where('id', $id)->first();
        $address->lat = $request->lat;
        $address->lon = $request->lon;
        $address->city = $request->city;
        $address->street = $request->has('street') ? $request->street : $address->street;
        $address->zip_code = $request->zip_code;
        $address->country = $request->country;
        $address->address = $request->address;
        $address->zone_id = $zone_id;
        $address->address_type = $request->address_type;
        $address->contact_person_name = $request->contact_person_name;
        $address->contact_person_number = $request->contact_person_number;
        $address->address_label = $request->address_label;
        $address->house = $request->house;
        $address->floor = $request->floor;
        $address->save();

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'guest_id' => $this->is_customer_logged_in ? 'nullable' : 'required|uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        if (!$this->address->where('id', $id)->where('user_id', $this->customer_user_id)->exists()) {
            return response()->json(response_formatter(DEFAULT_404), 404);
        }

        $this->address->where('id', $id)->update(['user_id' => null]);

        return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
    }

}
