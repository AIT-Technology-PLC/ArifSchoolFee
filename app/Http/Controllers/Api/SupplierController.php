<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Management');
    }

    public function index()
    {
        return Supplier::orderBy('company_name')->get();
    }
}
