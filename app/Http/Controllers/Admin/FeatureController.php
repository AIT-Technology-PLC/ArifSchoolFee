<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;

class FeatureController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $features = Feature::all();

        $totalEnabled = Feature::enabled()->count();

        $totalDisabled = Feature::disabled()->count();

        return view('admin.features.index', compact('features','totalEnabled','totalDisabled'));
    }
}
