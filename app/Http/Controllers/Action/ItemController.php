<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\ItemImport;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Item Type Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Item::class);

        ini_set('max_execution_time', '-1');

        (new BrandImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
