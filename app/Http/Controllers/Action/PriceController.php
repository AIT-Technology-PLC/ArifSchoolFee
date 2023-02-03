<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\PriceImport;
use App\Models\Price;

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

    public function changeStatus(Price $price)
    {
        $this->authorize('update', $price);

        if ($price->isActive()) {
            $price->update(['is_active' => 0]);
        } else {
            $price->update(['is_active' => 1]);
        }

        return back()->with('successMessage', 'Price status changed successfully.');
    }
}
