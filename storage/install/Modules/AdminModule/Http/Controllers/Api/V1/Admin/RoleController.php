<?php

namespace Modules\AdminModule\Http\Controllers\Api\V1\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\UserManagement\Entities\Role;
use function now;
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

        $roles = $this->role->when($request->has('status') && $request['status'] != 'all', function ($query) use ($request) {
            return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
        })->latest()->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        return response()->json(response_formatter(DEFAULT_200, $roles), 200);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles',
            'create' => 'required|in:1,0',
            'read' => 'required|in:1,0',
            'update' => 'required|in:1,0',
            'delete' => 'required|in:1,0',
            'modules' => 'required|array',
            'modules.*' => 'in:' . implode(',', array_column(SYSTEM_MODULES, 'key'))
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(USER_ROLE_CREATE_400, null, error_processor($validator)), 400);
        }

        DB::transaction(function () use ($request) {
            $role = $this->role;
            $role->role_name = $request['role_name'];
            $role->create = $request['create'];
            $role->read = $request['read'];
            $role->update = $request['update'];
            $role->delete = $request['delete'];
            $role->modules = $request['modules'];
            $role->save();
        });

        return response()->json(response_formatter(USER_ROLE_CREATE_200), 200);
    }

    /**
     * Show the form for editing the specified resource.
     * @param string $id
     * @return JsonResponse
     */
    public function edit(string $id): JsonResponse
    {
        $role = $this->role->where('id', $id)->first();
        return response()->json(response_formatter(DEFAULT_200, $role), 200);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_name' => 'required|unique:roles,role_name,' . $id,
            'create' => 'required|in:1,0',
            'read' => 'required|in:1,0',
            'update' => 'required|in:1,0',
            'delete' => 'required|in:1,0',
            'modules' => 'required|array',
            'modules.*' => 'in:' . implode(',', array_column(SYSTEM_MODULES, 'key'))
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(USER_ROLE_UPDATE_400, null, error_processor($validator)), 400);
        }

        DB::transaction(function () use ($request, $id) {
            $role = $this->role->where(['id' => $id])->first();
            $role->role_name = $request['role_name'];
            $role->create = $request['create'];
            $role->read = $request['read'];
            $role->update = $request['update'];
            $role->delete = $request['delete'];
            $role->modules = $request['modules'];
            $role->save();
        });

        return response()->json(response_formatter(USER_ROLE_UPDATE_200), 200);
    }

    /**
     * Remove the specified resource from storage.
     * @param Request $request
     * @return JsonResponse
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'role_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $roles = $this->role->whereIn('id', $request['role_ids']);
        if ($roles->count() > 0) {
            $roles->delete();
            return response()->json(response_formatter(DEFAULT_DELETE_200), 200);
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
            'role_ids' => 'required|array'
        ]);

        if ($validator->fails()) {
            return response()->json(response_formatter(DEFAULT_400, null, error_processor($validator)), 400);
        }

        $this->role->whereIn('id', $request['role_ids'])->update(['is_active' => $request['status']]);

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
        $roles = $this->role->where(function ($query) use ($keys) {
                foreach ($keys as $key) {
                    $query->orWhere('role_name', 'LIKE', '%' . $key . '%');
                }
            })->when($request['status'] != 'all', function ($query) use ($request) {
                return $query->ofStatus(($request['status'] == 'active') ? 1 : 0);
            })->paginate($request['limit'], ['*'], 'offset', $request['offset'])->withPath('');

        if ($roles->count() > 0) {
            return response()->json(response_formatter(DEFAULT_200, $roles), 200);
        }
        return response()->json(response_formatter(DEFAULT_204, $roles), 200);
    }
}
