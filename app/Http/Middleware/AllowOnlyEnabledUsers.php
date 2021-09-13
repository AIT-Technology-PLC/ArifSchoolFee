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
        if (userCompany()->isEnabled() && auth()->user()->isEnabled()) {
            return $next($request);
        }

        Auth::logout();

        return redirect("/login");
    }
}
