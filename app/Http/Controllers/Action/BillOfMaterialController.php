<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\BillOfMaterialImport;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');

    }

    public function approve(BillOfMaterial $billOfMaterial, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $billOfMaterial);

        if (!$billOfMaterial->billOfMaterialDetails()->count()) {
            return back()->with('failedMessage', 'This bill of material has no details therefore it cannot be approved.');
        }

        [$isExecuted, $message] = $action->execute($billOfMaterial);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function import(UploadImportFileRequest $request, BillOfMaterial $billOfMaterial)
    {
        $this->authorize('import', BillOfMaterial::class);

        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($request, $billOfMaterial) {
            $billOfMaterial->billOfMaterialDetails()->forceDelete();

            (new BillOfMaterialImport($billOfMaterial))->import($request->validated('file'));
        });

        return back()->with('imported', __('messages.file_imported'));
    }
}
