<?php

namespace Modules\AdminModule\Http\Middleware;

use Brian2694\Toastr\Facades\Toastr;
use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check() && in_array(auth()->user()->user_type, ADMIN_USER_TYPES)) {
            return $next($request);
        }
        Toastr::info(ACCESS_DENIED['message']);
        return redirect('admin/auth/login');
    }
}
