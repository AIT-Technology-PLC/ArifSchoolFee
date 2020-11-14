<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorPageController extends Controller
{
    public function getPermissionDeniedPage()
    {
        return view('errors.permission_denied');
    }
}
