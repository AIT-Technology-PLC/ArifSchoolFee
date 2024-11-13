<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\StudentGroup;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\StudentGroupImport;

class StudentGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Group');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', StudentGroup::class);

        ini_set('max_execution_time', '-1');

        (new StudentGroupImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }  
}
