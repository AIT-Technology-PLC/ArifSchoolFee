<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\StudentCategoryImport;
use App\Models\StudentCategory;

class StudentCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Category');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', StudentCategory::class);

        ini_set('max_execution_time', '-1');

        (new StudentCategoryImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
