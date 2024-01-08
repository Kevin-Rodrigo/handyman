<?php

namespace Modules\BidModule\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\BusinessSettingsModule\Entities\BusinessSettings;

class EnsureBiddingIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $bidding_status = BusinessSettings::where('key_name', 'bidding_status')->first()->live_values ?? 0;

        if (!$bidding_status) {
            return response()->json(DEFAULT_403);
        }
        return $next($request);
    }
}
