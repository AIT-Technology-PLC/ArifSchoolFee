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
        $isCompanyStandard = auth()->user()->employee->company->isCompanyStandardMember();
        $isCompanyPremiumOrProfessionalMember = auth()->user()->employee->company->isCompanyPremiumOrProfessionalMember();

        $canAccess = $isCompanyStandard || $isCompanyPremiumOrProfessionalMember;

        if ($canAccess) {
            return $next($request);
        }

        return redirect('/permission-denied');
    }
}
