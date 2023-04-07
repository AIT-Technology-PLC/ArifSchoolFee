<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\AdjustmentImport;
use App\Models\Adjustment;
use App\Notifications\AdjustmentApproved;
use App\Notifications\AdjustmentMade;
use App\Services\Models\AdjustmentService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    private $adjustmentService;

    public function __construct(AdjustmentService $adjustmentService)
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->adjustmentService = $adjustmentService;
    }

    public function approve(Adjustment $adjustment, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $adjustment);

        [$isExecuted, $message] = $action->execute($adjustment, AdjustmentApproved::class, 'Make Adjustment');

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function adjust(Adjustment $adjustment)
    {
        $this->authorize('adjust', $adjustment);

        [$isExecuted, $message] = $this->adjustmentService->adjust($adjustment, authUser());

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Adjustment', $adjustment->adjustmentDetails->pluck('warehouse_id'), $adjustment->createdBy),
            new AdjustmentMade($adjustment)
        );

        return back();
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Adjustment::class);

        ini_set('max_execution_time', '-1');

        DB::transaction(function () use ($request) {
            $adjustment = Adjustment::create([
                'code' => nextReferenceNumber('adjustments'),
                'issued_on' => now(),
            ]);

            (new AdjustmentImport($adjustment))->import($request->validated('file'));
        });

        return back()->with('imported', __('messages.file_imported'));
    }

    public function approveAndAdjust(Adjustment $adjustment)
    {
        $this->authorize('approve', $adjustment);

        $this->authorize('adjust', $adjustment);

        [$isExecuted, $message] = $this->adjustmentService->approveAndAdjust($adjustment, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Adjustment', $adjustment->adjustmentDetails->pluck('warehouse_id'), $adjustment->createdBy),
            new AdjustmentMade($adjustment)
        );

        return back();
    }
}