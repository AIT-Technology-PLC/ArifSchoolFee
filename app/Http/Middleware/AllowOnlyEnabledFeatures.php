<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AllowOnlyEnabledFeatures
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $featureName)
    {
        abort_if(!isFeatureEnabled($featureName), 403);

        return $next($request);
    }
}
