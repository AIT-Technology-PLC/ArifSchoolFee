<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\StaffImport;

class StaffController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Staff Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Staff::class);

        ini_set('max_execution_time', '-1');

        (new StaffImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
