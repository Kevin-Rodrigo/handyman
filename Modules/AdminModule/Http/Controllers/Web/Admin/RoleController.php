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
use Illuminate\Support\Facades\DB;
use Modules\UserManagement\Entities\Role;
use function response;
use function response_formatter;

class RoleController extends Controller
{
    private Role $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Application|Factory|View
     */
    public function create(Request $request): Application|Factory|View
    {
        $search = $request['search'];
        $query_param = $search ? ['search' => $request['search']] : '';

        $roles = $this->role->when($request->has('search'), function ($query) use ($request) {
            $keys = explode(' ', $request['search']);
            foreach ($keys as $key) {
                $query->orWhere('role_name', 'LIKE', '%' . $key . '%');
            }
        })->latest()->paginate(pagination_limit())->appends($query_param);
        return view('adminmodule::admin.employee.role-index', compact('roles', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'role_name' => 'required|unique:roles',
            'create' => 'in:1,0',
            'read' => 'in:1,0',
            'update' => 'in:1,0',
            'delete' => 'in:1,0',
            'modules' => 'required|array',
            'modules.*' => 'in:' . implode(',', array_column(SYSTEM_MODULES, 'key'))
        ]);

        DB::transaction(function () use ($request) {
            $role = $this->role;
            $role->role_name = $request['role_name'];
            $role->create = $request['create'] ?? 1;
            $role->read = $request['read'] ?? 1;
            $role->update = $request['update'] ?? 1;
            $role->delete = $request['delete'] ?? 1;
            $role->modules = $request['modules'];
            $role->save();
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
        $role = $this->role->where('id', $id)->first();
        return view('adminmodule::admin.employee.role-edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return RedirectResponse
     */
    public function update(Request $request, string $id): RedirectResponse
    {
        $request->validate([
            'role_name' => 'required|unique:roles,role_name,' . $id,
            'create' => 'in:1,0',
            'read' => 'in:1,0',
            'update' => 'in:1,0',
            'delete' => 'in:1,0',
            'modules' => 'required|array',
            'modules.*' => 'in:' . implode(',', array_column(SYSTEM_MODULES, 'key'))
        ]);

        DB::transaction(function () use ($request, $id) {
            $role = $this->role->where(['id' => $id])->first();
            $role->role_name = $request['role_name'];
            $role->create = $request['create'] ?? 1;
            $role->read = $request['read'] ?? 1;
            $role->update = $request['update'] ?? 1;
            $role->delete = $request['delete'] ?? 1;
            $role->modules = $request['modules'];
            $role->save();
        });

        Toastr::success(USER_ROLE_UPDATE_200['message']);
        return redirect('/admin/role/create');
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function destroy(Request $request, $id): RedirectResponse
    {
        $this->role->where('id', $id)->delete();
        Toastr::success(DEFAULT_DELETE_200['message']);
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
        $role = $this->role->where('id', $id)->first();
        $this->role->where('id', $id)->update(['is_active' => !$role->is_active]);

        return response()->json(DEFAULT_STATUS_UPDATE_200, 200);
    }
}
