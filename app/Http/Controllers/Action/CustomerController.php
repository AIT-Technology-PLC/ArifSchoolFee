<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\CustomerImport;
use Maatwebsite\Excel\Facades\Excel;

class CustomerController extends Controller
{
    public function import()
    {
        Excel::import(new CustomerImport, request()->file('file'));

        return redirect()->route('customers.index');
    }
}