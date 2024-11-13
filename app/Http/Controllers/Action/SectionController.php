<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\SectionImport;
use App\Models\Section;

class SectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Section Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Section::class);

        ini_set('max_execution_time', '-1');

        (new SectionImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
