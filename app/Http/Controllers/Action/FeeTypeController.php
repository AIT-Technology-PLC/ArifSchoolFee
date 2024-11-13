<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\FeeTypeImport;
use App\Models\FeeType;

class FeeTypeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Type');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', FeeType::class);

        ini_set('max_execution_time', '-1');

        (new FeeTypeImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
