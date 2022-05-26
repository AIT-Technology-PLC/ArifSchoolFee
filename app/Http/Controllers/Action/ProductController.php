<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\ProductImport;
use App\Models\Product;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Product::class);

        ini_set('max_execution_time', '-1');

        (new ProductImport)->import($request->safe()['file']);

        return back()->with('imported', __('messages.file_imported'));
    }
}
