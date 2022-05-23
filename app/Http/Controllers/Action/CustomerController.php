<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\CustomerImport;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Customer::class);

        ini_set('max_execution_time', '-1');

        (new CustomerImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}