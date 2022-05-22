<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\ProductCategoryImport;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', ProductCategory::class);

        ini_set('max_execution_time', '-1');

        (new ProductCategoryImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}
