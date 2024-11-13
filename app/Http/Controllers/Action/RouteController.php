<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\RouteImport;
use App\Models\Route;

class RouteController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Route Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Route::class);

        ini_set('max_execution_time', '-1');

        (new RouteImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
