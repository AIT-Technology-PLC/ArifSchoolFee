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

        (new EmployeeImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}
