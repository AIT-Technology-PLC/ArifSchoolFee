<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Feature;

class ToggleFeatureController extends Controller
{
    public function __invoke(Feature $feature)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);
        
        $feature->toggle();

        return back()->with('successMessage', 'Feature is ' . $feature->isEnabled() ? 'enabled.' : 'disabled.');
    }
}
