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

        (new TenderStatusImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}