<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\DesignationImport;
use App\Models\Designation;

class DesignationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Designation Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Designation::class);

        ini_set('max_execution_time', '-1');

        (new DesignationImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
