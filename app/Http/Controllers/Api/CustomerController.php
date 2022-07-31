<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');
    }

    public function index()
    {
        return Customer::orderBy('company_name')->get();
    }
}
