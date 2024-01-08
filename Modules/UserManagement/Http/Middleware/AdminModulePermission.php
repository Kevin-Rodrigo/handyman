<?php

namespace Modules\UserManagement\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class AdminModulePermission
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param $module
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $module): mixed
    {
        if ($request->user()->user_type == 'super-admin') {
            return $next($request);
        } elseif ($request->user()->roles->count() > 0) {

            $role = $request->user()->roles[0];
            $modules = $request->user()->roles[0]->modules;

            if (in_array($module, $modules)) {
                if ($role->create && $request->isMethod('post')) {
                    return $next($request);
                } elseif ($role->read && $request->isMethod('get')) {
                    return $next($request);
                } elseif ($role->update && ($request->isMethod('put') || $request->isMethod('patch'))) {
                    return $next($request);
                } elseif ($role->delete && $request->isMethod('delete')) {
                    return $next($request);
                }
            }
        }

        Toastr::warning(ACCESS_DENIED['message']);
        return back();
    }
}
