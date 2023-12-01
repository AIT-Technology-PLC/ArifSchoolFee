<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (authUser()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('menu.index');
    }
}
