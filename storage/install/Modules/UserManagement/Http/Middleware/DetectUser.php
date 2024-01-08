<?php

namespace Modules\UserManagement\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\UserManagement\Entities\User;

class DetectUser
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('access_token')) {
            $user = User::find(base64_decode($request['access_token']));
            if ($user) {
                $request['user'] = $user;
                return $next($request);
            }
            return response()->json(response_formatter(DEFAULT_401));
        }
        return response()->json(response_formatter(DEFAULT_401));
    }
}
