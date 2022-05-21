<?php

namespace App\Http\Controllers\Action;

use App\Models\Grn;
use App\Imports\GrnImport;
use App\Utilities\Notifiables;
use App\Notifications\GrnAdded;
use App\Notifications\GrnApproved;
use App\Services\Models\GrnService;
use App\Http\Controllers\Controller;
use App\Actions\ApproveTransactionAction;
use Illuminate\Support\Facades\Notification;
use App\Http\Requests\UploadImportFileRequest;

class GrnController extends Controller
{
    private $grnService;

    public function __construct(GrnService $grnService)
    {
        $this->middleware('isFeatureAccessible:Grn Management');

        $this->grnService = $grnService;
    }

    public function approve(Grn $grn, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $grn);

        [$isExecuted, $message] = $action->execute($grn, GrnApproved::class, 'Add GRN');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Grn $grn)
    {
        $this->authorize('add', $grn);

        [$isExecuted, $message] = $this->grnService->add($grn, auth()->user());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GRN', $grn->grnDetails->pluck('warehouse_id'), $grn->createdBy),
            new GrnAdded($grn)
        );

        return back();
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Grn::class);

        (new GrnImport)->import($request->safe()['file']);

        return back()->with('imported', 'File uploaded succesfully !');
    }
}
