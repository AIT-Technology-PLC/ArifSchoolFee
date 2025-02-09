<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Limit;

class LimitController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $limits = Limit::all();

        return view('admin.limits.index', compact('limits'));
    }
}
