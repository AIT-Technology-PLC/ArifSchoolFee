<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\EmployeeTransfer;
use App\Notifications\EmployeeTransferApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class EmployeeTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Employee Transfer');
    }

    public function approve(EmployeeTransfer $employeeTransfer, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $employeeTransfer);

        // dd($employeeTransfer->employeeTransferDetails()->get(['warehouse_id']));
        // dd($employeeTransfer->employeeTransferDetails->employee->user()->get(['warehouse_id']));

        [$isExecuted, $message] = $action->execute($employeeTransfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read GDN', $employeeTransfer->employeeTransferDetails->pluck('warehouse_id'), $employeeTransfer->createdBy),
            new EmployeeTransferApproved($employeeTransfer)
        );

        return back()->with('successMessage', $message);
    }
}
