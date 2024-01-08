<?php

namespace Modules\AdminModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Http\Requests\ProviderStoreRequest;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;

class EmployeeController extends Controller
{
    protected User $employee;
    protected UserAddress $address;

    public function __construct(User $employee, UserAddress $address)
    {
        $this->employee = $employee;
        $this->address = $address;
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

        $providers = $this->employee->OfType(['admin-employee'])->with(['roles', 'zones', 'addresses'])
            ->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $providers), 200);
    }


    /**
     * Store a newly created resource in storage.
     * @param ProviderStoreRequest $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'confirm_password' => 'required|same:password',
            'profile_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'required|in:passport,driving_licence,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'role_id' => 'required|uuid',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $identity_images = [];
        foreach ($request->identity_images as $image) {
            $identity_images[] = file_uploader('employee/identity/', 'png', $image);
        }

        DB::transaction(function () use ($request, $identity_images) {
            $employee = $this->employee;
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            $employee->profile_image = file_uploader('employee/profile/', 'png', $request->file('profile_image'));
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            $employee->identification_image = $identity_images;
            $employee->password = bcrypt($request->password);
            $employee->user_type = 'admin-employee';
            $employee->is_active = 1;
            $employee->save();

            $employee->roles()->sync([$request['role_id']]);
            $employee->zones()->sync($request['zone_ids']);

            $address = $this->address;
            $address->user_id = $employee->id;
            $address->address = $request->address;
            $address->save();
        });

        return response()->json(response_formatter(DEFAULT_STORE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $employee = $this->employee->with(['roles', 'zones', 'addresses'])->where(['id' => $id, 'user_type' => 'admin-employee'])->first();

        if (isset($employee)) {
            return response()->json(response_formatter(DEFAULT_200, $employee), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $employee = $this->employee->where(['id' => $id, 'user_type' => 'admin-employee'])->first();

        if (!isset($employee)) {
            return response()->json(response_formatter(DEFAULT_204), 200);
        }

        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'required|unique:users,phone,' . $employee->id,
            'password' => 'min:8',
            'confirm_password' => 'same:password',
            'profile_image' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'required|in:passport,driving_licence,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'role_id' => 'required|uuid',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',
            'address' => 'required|string'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }
        $identity_images = (array)$employee->identification_image;
        if ($request->has('identity_images')) {
            foreach ($request['identity_images'] as $image) {
                $identity_images[] = file_uploader('employee/identity/', 'png', $image);
            }
            $employee->identification_image = $identity_images;
        }
        DB::transaction(function () use ($id, $employee, $request, $identity_images) {
            $employee->first_name = $request->first_name;
            $employee->last_name = $request->last_name;
            $employee->email = $request->email;
            $employee->phone = $request->phone;
            if ($request->has('profile_image')) {
                $employee->profile_image = file_uploader('employee/profile/', 'png', $request->file('profile_image'), $employee->profile_image);;
            }
            $employee->identification_number = $request->identity_number;
            $employee->identification_type = $request->identity_type;
            if ($request->has('password')) {
                $employee->password = bcrypt($request->password);
            }
            $employee->user_type = 'admin-employee';
            $employee->save();

            $employee->roles()->sync([$request['role_id']]);
            $employee->zones()->sync($request['zone_ids']);

            $address = $this->address->where('user_id', $id)->first();
            $address->address = $request->address;
            $address->save();
        });

        return response()->json(response_formatter(PROVIDER_STORE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $employees = $this->employee->where(['user_type' => 'admin-employee'])->whereIn('id', $request['employee_ids']);
        if ($employees->count() > 0) {
            foreach ($employees->get() as $employee) {
                file_remover('employee/profile/', $employee->profile_image);
                foreach ($employee->identification_image as $image) {
                    file_remover('employee/identity/', $image);
                }
                $employee->roles()->sync([]);
                $employee->zones()->sync([]);
                $employee->addresses()->delete();
            }
            $employees->forceDelete();
            return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function remove_image(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'employee_id' => 'required|uuid',
            'image_name' => 'required|string',
            'image_type' => 'required|in:logo,identity_image'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $employee = $this->employee->where('id', $request['employee_id'])->first();
        if ($request['image_type'] == 'identity_image') {
            file_remover('employee/identity/', $request['image_name']);
            $employee->identification_image = array_diff($employee->identification_image, $request['image_name']);
            $employee->save();
        }
        return response()->json(response_formatter(DEFAULT_204), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function status_update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:1,0',
            'employee_ids' => 'required|array',
            'employee_ids.*' => 'uuid',
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->employee->where(['user_type' => 'admin-employee'])->whereIn('id', $request['employee_ids'])->update(['is_active' => $request['status']]);

        return response()->json(response_formatter(DEFAULT_STATUS_UPDATE_200), 200);
    }

    /**
     * Show the specified resource.
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'string' => 'required',
            'limit' => 'required|numeric|min:1|max:200',
            'offset' => 'required|numeric|min:1|max:100000',
            'status' => 'required|in:all,active,inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $keys = explode(' ', base64_decode($request['string']));
        $employees = $this->employee->where(['user_type' => 'admin-employee'])->with(['roles', 'zones', 'addresses'])
            ->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere('first_name', 'LIKE', '%' . $key . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $key . '%')
                        ->orWhere('email', 'LIKE', '%' . $key . '%')
                        ->orWhere('identification_number', 'LIKE', '%' . $key . '%')
                        ->orWhere('phone', 'LIKE', '%' . $key . '%');
                }
            })->when($request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($employees->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, $employees), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $employees), 200);
    }
}
