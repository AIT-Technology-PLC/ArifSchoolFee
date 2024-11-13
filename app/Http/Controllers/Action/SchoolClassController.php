<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\SchoolClassImport;
use App\Models\SchoolClass;

class SchoolClassController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Class Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', SchoolClass::class);

        ini_set('max_execution_time', '-1');

        (new SchoolClassImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
