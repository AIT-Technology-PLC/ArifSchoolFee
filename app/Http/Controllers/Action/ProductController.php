<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadFileImportRequest;
use App\Imports\ProductImport;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');

        $this->authorizeResource(Product::class, 'product');
    }

    public function import(UploadFileImportRequest $request)
    {
        $file = $request->file('file');
        $import = new ProductImport;
        $import->import($file);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}
