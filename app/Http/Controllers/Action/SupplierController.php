<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\SupplierImport;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Supplier::class);

        (new SupplierImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}