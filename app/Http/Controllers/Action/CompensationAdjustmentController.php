<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\CancelTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\CompensationAdjustment;
use App\Models\User;
use App\Notifications\CompensationAdjustmentApproved;
use App\Notifications\CompensationAdjustmentCancelled;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class CompensationAdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Adjustment');
    }

    public function approve(CompensationAdjustment $compensationAdjustment, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $compensationAdjustment);

        if (!authUser()->hasWarehousePermission('hr', User::whereHas('employee', fn($q) => $q->whereIn('id', $compensationAdjustment->compensationAdjustmentDetails->pluck('employee_id')))->pluck('warehouse_id')->values()->all())) {
            return back()->with('failedMessage', 'You do not have permission to approve this adjustment request.');
        }

        if ($compensationAdjustment->isCancelled()) {
            return back()->with('failedMessage', 'You can not approve an adjustment that is cancelled.');
        }

        [$isExecuted, $message] = $action->execute($compensationAdjustment, CompensationAdjustmentApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Compensation Adjustment', $compensationAdjustment->warehouse_id, $compensationAdjustment->createdBy),
            new CompensationAdjustmentApproved($compensationAdjustment)
        );

        return back()->with('successMessage', $message);
    }

    public function cancel(CompensationAdjustment $compensationAdjustment, CancelTransactionAction $action)
    {
        $this->authorize('cancel', $compensationAdjustment);

        [$isExecuted, $message] = $action->execute($compensationAdjustment);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Compensation Adjustment', $compensationAdjustment->warehouse_id, $compensationAdjustment->createdBy),
            new CompensationAdjustmentCancelled($compensationAdjustment)
        );

        return back()->with('successMessage', $message);
    }
}