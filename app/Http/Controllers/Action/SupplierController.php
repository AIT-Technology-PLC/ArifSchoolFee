<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\SupplierImport;
use Maatwebsite\Excel\Facades\Excel;

class SupplierController extends Controller
{
    public function import()
    {
        Excel::import(new SupplierImport, request()->file('file'));

        return redirect()->route('supplier.index');
    }
}