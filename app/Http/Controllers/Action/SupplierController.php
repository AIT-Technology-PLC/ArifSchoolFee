<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Imports\SupplierImport;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Management');

        $this->authorizeResource(Supplier::class, 'supplier');
    }

    public function import()
    {
        $this->authorize(Supplier::class, 'import');

        (new SupplierImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}