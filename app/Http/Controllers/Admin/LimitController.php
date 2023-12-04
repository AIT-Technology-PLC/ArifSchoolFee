<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Limit;

class LimitController extends Controller
{
    public function index()
    {
        $limits = Limit::all();

        return view('admin.limits.index', compact('limits'));
    }
}
