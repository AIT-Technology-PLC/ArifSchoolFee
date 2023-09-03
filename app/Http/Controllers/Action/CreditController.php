<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\CreditImport;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Credit::class);

        ini_set('max_execution_time', '-1');

        (new CreditImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
