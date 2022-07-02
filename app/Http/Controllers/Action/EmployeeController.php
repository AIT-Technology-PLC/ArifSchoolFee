<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\EmployeeImport;
use App\Models\Employee;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Employee::class);

        ini_set('max_execution_time', '-1');

        (new EmployeeImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
