<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\DepartmentImport;
use App\Models\Department;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Department Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Department::class);

        ini_set('max_execution_time', '-1');

        (new DepartmentImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
