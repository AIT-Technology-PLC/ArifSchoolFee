<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\FeeGroupImport;
use App\Models\FeeGroup;

class FeeGroupController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Fee Group');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', FeeGroup::class);

        ini_set('max_execution_time', '-1');

        (new FeeGroupImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
