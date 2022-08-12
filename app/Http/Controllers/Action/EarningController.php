<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Models\User;
use App\Notifications\EarningApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class EarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Earning Management');
    }

    public function approve(Earning $earning, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $earning);

        if (!authUser()->hasWarehousePermission('hr', User::whereHas('employee', fn($q) => $q->whereIn('id', $earning->earningDetails->pluck('employee_id')))->pluck('warehouse_id'))) {
            return back()->with('failedMessage', 'You do not have permission to approve this earning request.');
        }

        [$isExecuted, $message] = $action->execute($earning, EarningApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Earning', $earning->warehouse_id, $earning->createdBy),
            new EarningApproved($earning)
        );

        return back()->with('successMessage', $message);
    }
}
