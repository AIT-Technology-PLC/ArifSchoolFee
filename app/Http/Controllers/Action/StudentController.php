<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\StudentImport;

class StudentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Student::class);

        ini_set('max_execution_time', '-1');

        (new StudentImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
