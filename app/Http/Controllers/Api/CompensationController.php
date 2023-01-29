<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Compensation;

class CompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Management');
    }

    public function index()
    {
        $this->authorize('viewAny', Compensation::class);

        return Compensation::active()->canBeInputtedManually()->adjustable()->orderBy('name')->get();
    }
}
