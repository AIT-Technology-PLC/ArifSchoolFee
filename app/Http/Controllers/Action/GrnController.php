<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\GrnImport;
use App\Models\Grn;
use App\Notifications\GrnAdded;
use App\Notifications\GrnApproved;
use App\Services\Models\GrnService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Grn $grn)
    {
        $this->authorize('add', $grn);

        [$isExecuted, $message] = $this->grnService->add($grn, authUser());

        if (! $isExecuted) {
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

        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($request) {
            $grn = Grn::create([
                'code' => nextReferenceNumber('grns'),
                'issued_on' => now(),
            ]);

            (new GrnImport($grn))->import($request->validated('file'));
        });

        return back()->with('imported', __('messages.file_imported'));
    }

    public function approveAndAdd(Grn $grn)
    {
        $this->authorize('approve', $grn);

        $this->authorize('add', $grn);

        [$isExecuted, $message] = $this->grnService->approveAndAdd($grn, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GRN', $grn->grnDetails->pluck('warehouse_id'), $grn->createdBy),
            new GrnAdded($grn)
        );

        return back();
    }
}