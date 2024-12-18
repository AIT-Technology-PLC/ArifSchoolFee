<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowOnlyCallCenterUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        if (authUser()->isCallCenter()) {
            authUser()->updateLastOnlineAt();
            return $next($request);
        }

        Auth::logout();

        return redirect('/login');
    }
}
