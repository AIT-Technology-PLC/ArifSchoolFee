<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyPremiumMembers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isCompanyPremiumMember = userCompany()->isCompanyPremiumMember();

        if ($isCompanyPremiumMember) {
            return $next($request);
        }

        return response()->view('errors.permission_denied');
    }
}
