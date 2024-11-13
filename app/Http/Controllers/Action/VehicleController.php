<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\VehicleImport;
use App\Models\Vehicle;

class VehicleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Vehicle Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Vehicle::class);

        ini_set('max_execution_time', '-1');

        (new vehicleImport)->import($request->validated('file'));

        return back()->with('imported', __('messages.file_imported'));
    }
}
