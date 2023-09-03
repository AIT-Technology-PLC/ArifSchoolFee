<?php

namespace App\Http\Controllers\Action;

use App\Models\Debt;
use App\Imports\DebtImport;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;

class DebtController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Debt Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Debt::class);

        ini_set('max_execution_time', '-1');

        (new DebtImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
