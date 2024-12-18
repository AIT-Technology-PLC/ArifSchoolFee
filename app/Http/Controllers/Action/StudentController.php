<?php

namespace App\Http\Controllers\Action;

use App\DataTables\StudentHistoryDatatable;
use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\StudentImport;
use App\Models\StudentHistory;

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

    public function history(StudentHistoryDatatable $datatable, Student $student)
    {
        $datatable = new StudentHistoryDatatable($student->id);

        $datatable->builder()->setTableId('student-histories-datatable')->orderBy(0, 'asc');

        return $datatable->render('students.history', compact('student'));
    }
}