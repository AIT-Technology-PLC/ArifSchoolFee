<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AllowOnlyBankUsers
{
    public function handle(Request $request, Closure $next): Response
    {
        if (authUser()->isBank()) {
            authUser()->updateLastOnlineAt();
            return $next($request);
        }

        Auth::logout();

        return redirect('/login');
    }
}
