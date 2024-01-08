<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Config;
use Modules\ZoneManagement\Entities\Zone;

class ZoneAdder
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (request()->is('api/*/customer?*') || request()->is('api/*/customer/*')) {
            Config::set('zone_id', $request->header('zoneid') ?? null);
            if (preg_match('/^[0-9A-F]{8}-[0-9A-F]{4}-4[0-9A-F]{3}-[89AB][0-9A-F]{3}-[0-9A-F]{12}$/i', Config::get('zone_id'))) {
                $zone = Zone::ofStatus(1)->where('id', $request->header('zoneid'))->first();
                if (!isset($zone)) {
                    return response()->json(response_formatter(ZONE_404), 401);
                }
            }
        }
        return $next($request);
    }
}
