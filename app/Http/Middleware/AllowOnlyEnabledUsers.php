<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AllowOnlyEnabledUsers
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
        if ($this->isCompanyEnabled()) {
            if ($this->isUserEnabled()) {
                return $next($request);
            }
        }

        Auth::logout();
        return redirect("/login");
    }

    public function isCompanyEnabled()
    {
        $isCompanyEnabled = userCompany()->enabled;

        return $isCompanyEnabled;
    }

    public function isUserEnabled()
    {
        $isUserEnabled = auth()->user()->employee->enabled;

        return $isUserEnabled;
    }
}
