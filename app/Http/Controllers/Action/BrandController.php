<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\BrandImport;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Brand Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Brand::class);

        ini_set('max_execution_time', '-1');

        (new BrandImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
