<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use App\Models\Customer;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->authorizeResource(Customer::class, 'customer');
    }

    public function import()
    {
        $this->authorize(Customer::class, 'import');

        (new CustomerImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}