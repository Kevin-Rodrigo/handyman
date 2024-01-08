<?php

namespace Modules\ServicemanModule\Http\Controllers\Api\V1\Provider;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\BookingModule\Entities\Booking;
use Modules\UserManagement\Entities\Serviceman;
use Modules\UserManagement\Entities\User;

class ServicemanController extends Controller
{
    private User $employee;
    private Serviceman $serviceman;
    private Booking $booking;

    public function __construct(Serviceman $serviceman, User $employee, Booking $booking)
    {
        $this->serviceman = $serviceman;
        $this->employee = $employee;
        $this->booking = $booking;
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
            'status' => 'required|in:active,inactive,all'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $serviceman = $this->employee->with(['serviceman'])
            ->when($request->has('string'), function ($query) use ($request) {
                $query->where(function ($query) use ($request) {
                    $keys = explode(' ', base64_decode($request['string']));
                    foreach ($keys as $key) {
                        $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                            ->orWhere('email', 'LIKE', '%' . $key . '%')
                            ->orWhere('identification_number', 'LIKE', '%' . $key . '%')
                            ->orWhere('phone', 'LIKE', '%' . $key . '%');
                    }
                });
            })
            ->when($request['status'] != 'all', function ($query) use ($request) {
                $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })
            ->whereHas('serviceman', function ($query) use ($request) {
                $query->where('provider_id', $request->user()->provider->id);
            })->latest()->where(['user_type' => 'provider-serviceman'])
            ->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $serviceman), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'required|in:passport,driving_license,company_id,nid,trade_license',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $identity_images = [];
        foreach ($request->identity_images as $image) {
            $identity_images[] = file_uploader('serviceman/identity/', 'png', $image);
        }

        DB::transaction(function () use ($request, $identity_images) {
            $employee = $this->employee;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->profile_image = file_uploader('serviceman/profile/', 'png', $request->file('profile_image'));
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            $employee->identification_image = $identity_images;
            $employee->password = bcrypt($request->password);
            $employee->user_type = 'provider-serviceman';
            $employee->is_active = 1;
            $employee->save();

            $serviceman = $this->serviceman;
            $serviceman->provider_id = $request->user()->provider->id;
            $serviceman->user_id = $employee->id;
            $serviceman->save();
        });

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }

    /**
     * Show the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function show(string $id): JsonResponse
    {
        $bookings = $this->booking->where('serviceman_id', $id)
            ->select('booking_status', DB::raw('count(*) as total_booking'))
            ->groupBy('booking_status')
            ->get();

        $booking_count = ['ongoing'=>0,'completed'=>0,'canceled'=>0];
        foreach ($bookings as $booking) {
            if ($booking->booking_status == 'ongoing')
                $booking_count['ongoing'] = $booking->total_booking ?? 0;
            if ($booking->booking_status == 'completed')
                $booking_count['completed'] = $booking->total_booking ?? 0;
            if ($booking->booking_status == 'canceled')
                $booking_count['canceled'] = $booking->total_booking ?? 0;
        }

        $serviceman = $this->serviceman::with(['user'])->find($id);
        $serviceman->bookings_count = $booking_count;

        if (!isset($serviceman)) {
            return response()->json(response_formatter(DEFAULT_204), 204);
        }
        return response()->json(response_formatter(DEFAULT_200, $serviceman), 200);
    }

    /**
     * Show the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $serviceman = $this->serviceman::with(['users'])->where('id', $id)->first();

        if (!isset($serviceman)) {
            return response()->json(response_formatter(DEFAULT_204), 204);
        }
        return response()->json(response_formatter(DEFAULT_200, $serviceman), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $employee = $this->employee::whereHas('serviceman', function ($query) use ($id) {
            $query->where(['id' => $id]);
        })->first();

        if (!isset($employee)) {
            return response()->json(response_formatter(DEFAULT_204), 204);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required|unique:users,phone,' . $employee->id,
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'password' => 'min:8',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'in:passport,driving_license,company_id,nid,trade_license',
            'identity_number' => 'required',
            'identity_images' => 'array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $identity_images = [];
        if ($request->has('identity_images')) {
            foreach ($request['identity_images'] as $image) {
                $identity_images[] = file_uploader('serviceman/identity/', 'png', $image);
            }
        }

        DB::transaction(function () use ($request, $identity_images, $employee) {
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            if ($request->has('profile_image')) {
                $employee->profile_image = file_uploader('serviceman/profile/', 'png', $request->file('profile_image'));
            }
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            if(count($identity_images) > 0) {
                $employee->identification_image = $identity_images;
            }
            if ($request->has('password')) {
                $employee->password = bcrypt($request->password);
            }
            $employee->user_type = 'provider-serviceman';
            $employee->save();
        });

        return response()->json(response_formatter(DEFAULT_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'serviceman_id' => 'required|array',
            'serviceman_id.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $serviceman_ids = $this->serviceman->whereIn('id', $request->serviceman_id)->pluck('user_id')->toArray();
        if (count($serviceman_ids) < 1) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }
        $this->serviceman->whereIn('id', $request->serviceman_id)->delete();

        $employees = $this->employee->whereIn('id', $serviceman_ids);
        if ($employees->count() > 0) {
            foreach ($employees->get() as $employee) {
                file_remover('serviceman/profile/', $employee->profile_image);
                foreach ($employee->identification_image as $image) {
                    file_remover('serviceman/identity/', $image);
                }
                $employee->forceDelete();
            }
            return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * * Bulk status update
     * @param Request $request
     * @return JsonResponse
     */
    public function change_active_status(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'serviceman_id' => 'required|array',
            'serviceman_id.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }


        $serviceman_ids = $this->serviceman->whereIn('id', $request->serviceman_id)->pluck('user_id')->toArray();
        if (count($serviceman_ids) < 1) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        $employees = $this->employee->whereIn('id', $serviceman_ids)->get();

        foreach ($employees as $employee) {
            if(isset($employee)) {
                $employee->is_active = !$employee->is_active;
                $employee->save();
            }
        }

        return response()->json(response_formatter(DEFAULT_200), 200);
    }
}
