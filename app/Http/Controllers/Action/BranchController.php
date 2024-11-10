<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\BranchImport;

class BranchController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Branch Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Warehouse::class);

        ini_set('max_execution_time', '-1');

        (new BranchImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
