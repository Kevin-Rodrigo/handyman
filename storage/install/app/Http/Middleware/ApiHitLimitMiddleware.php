<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApiHitLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param string $attempt_type
     * @param int $hit_count
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next, string $attempt_type = "normal", int $hit_count = 10)
    {
        $key = optional($request->user())->id ?: $request->ip();
        if (Cache::has($key)) {
            return response()->json(TOO_MANY_ATTEMPT_403);
        } else {
            Cache::store('file')->put(optional($request->user())->id ?: $request->ip(), $attempt_type, 10);
        }
        return $next($request);
    }
}
