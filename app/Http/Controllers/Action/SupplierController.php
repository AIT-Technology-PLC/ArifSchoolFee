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

        ini_set('max_execution_time', '-1');

        (new SupplierImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
