<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\PriceImport;

class PriceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Price Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Price::class);

        ini_set('max_execution_time', '-1');

        (new PriceImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
