<?php

namespace App\Http\Middleware;

use Closure;

class AllowOnlyStandardMembers
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
        $isCompanyStandard = userCompany()->isCompanyStandardMember();
        $isCompanyPremiumOrProfessionalMember = userCompany()->isCompanyPremiumOrProfessionalMember();

        $canAccess = $isCompanyStandard || $isCompanyPremiumOrProfessionalMember;

        if ($canAccess) {
            return $next($request);
        }

        return response()->view('errors.permission_denied');
    }
}
