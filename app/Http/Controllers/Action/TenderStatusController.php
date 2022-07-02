<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\TenderStatusImport;
use App\Models\TenderStatus;

class TenderStatusController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Tender Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', TenderStatus::class);

        ini_set('max_execution_time', '-1');

        (new TenderStatusImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
