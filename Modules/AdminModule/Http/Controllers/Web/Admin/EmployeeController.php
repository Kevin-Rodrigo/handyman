<?php

namespace Modules\AdminModule\Http\Controllers\Web\Admin;

use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\ProviderManagement\Http\Requests\ProviderStoreRequest;
use Modules\UserManagement\Entities\Role;
use Modules\UserManagement\Entities\User;
use Modules\UserManagement\Entities\UserAddress;
use Modules\ZoneManagement\Entities\Zone;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function bcrypt;
use function file_remover;
use function file_uploader;
use function response;
use function response_formatter;

class EmployeeController extends Controller
{
    protected User $employee;
    protected UserAddress $address;
    protected Role $role;
    protected Zone $zone;

    public function __construct(User $employee, UserAddress $address, Role $role, Zone $zone)
    {
        $this->employee = $employee;
        $this->address = $address;
        $this->role = $role;
        $this->zone = $zone;
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): Application|Factory|View
    {
        $roles = $this->role->where(['is_active' => 1])->get();
        $zones = $this->zone->where(['is_active' => 1])->get();

        return view('adminmodule::admin.employee.create', compact('roles', 'zones'));
    }


    /**
     * Display a listing of the resource.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function index(Request $request): Application|Factory|View
    {
        $search = $request->has('search') ? $request['search'] : '';
        $status = $request->has('status') ? $request['status'] : 'all';
        $query_param = ['search' => $search, 'status' => $status];

        $employees = $this->employee->OfType(['admin-employee'])->with(['roles', 'zones', 'addresses'])
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
            })
            ->latest()->paginate(pagination_limit())->appends($query_param);

        return view('adminmodule::admin.employee.list', compact('employees', 'status', 'search'));
    }


    /**
     * Store a newly created resource in storage.
     * @param ProviderStoreRequest $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|unique:users,phone',
            'password' => 'required',
            'profile_image' => 'required|image|mimes:jpeg,jpg,png,gif|max:10000',
            'identity_type' => 'required|in:passport,driving_license,nid,trade_license,company_id',
            'identity_number' => 'required',
            'identity_images' => 'required|array',
            'identity_images.*' => 'image|mimes:jpeg,jpg,png,gif|max:10000',
            'role_id' => 'required|uuid',
            'zone_ids' => 'required|array',
            'zone_ids.*' => 'uuid',
            'address' => 'required|string'
        ]);

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

        Toastr::success(DEFAULT_STORE_200['message']);
        return back();
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return Application|Factory|View
     */
    public function edit(string $id): Application|Factory|View
    {
        $employee = $this->employee->with(['roles', 'zones', 'addresses'])->where(['id' => $id, 'user_type' => 'admin-employee'])->first();
        $roles = $this->role->where(['is_active' => 1])->get();
        $zones = $this->zone->where(['is_active' => 1])->get();

        return view('adminmodule::admin.employee.edit', compact('roles', 'zones', 'employee'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return Redirector|Application|RedirectResponse
     */
    public function update(Request $request, string $id): Application|RedirectResponse|Redirector
    {
        $employee = $this->employee->where(['id' => $id, 'user_type' => 'admin-employee'])->first();

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,' . $employee->id,
            'phone' => 'required|unique:users,phone,' . $employee->id,
            'password' => !is_null($request->password) ? 'string|min:8' : '',
            'confirm_password' => !is_null($request->password) ? 'required|same:password' : '',
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

        /*$identity_images = (array)$employee->identification_image;*/
        $identity_images = [];
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
            if (!is_null($request->password)) {
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

        Toastr::success(DEFAULT_UPDATE_200['message']);
        return back();
        return redirect('admin/employee/list');
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $user = $this->employee->where('id', $id)->first();
        if (isset($user)) {
            file_remover('employee/profile_image/', $user->profile_image);
            foreach ($user->identification_image as $image_name) {
                file_remover('employee/identity/', $image_name);
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
        $user = $this->employee->where('id', $id)->first();
        $this->employee->where('id', $id)->update(['is_active' => !$user->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
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
     * Display a listing of the resource.
     * @param Request $request
     * @return string|StreamedResponse
     */
    public function download(Request $request): string|StreamedResponse
    {
        $items = $this->employee->OfType(['admin-employee'])->with(['roles', 'zones', 'addresses'])
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
}
