<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\WarehouseImport;

class WarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Warehouse Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Warehouse::class);

        ini_set('max_execution_time', '-1');

        (new WarehouseImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}