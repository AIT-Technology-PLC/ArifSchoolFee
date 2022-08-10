<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\EmployeeTransfer;
use App\Notifications\EmployeeTransferApproved;
use App\Services\Models\EmployeeTransferService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class EmployeeTransferController extends Controller
{
    private $employeeTransferService;

    public function __construct(EmployeeTransferService $employeeTransferService)
    {
        $this->middleware('isFeatureAccessible:Employee Transfer');

        $this->employeeTransferService = $employeeTransferService;
    }

    public function approve(EmployeeTransfer $employeeTransfer)
    {
        $this->authorize('approve', $employeeTransfer);

        foreach ($employeeTransfer->employeeTransferDetails as $employeeTransferDetail) {
            if (!authUser()->hasWarehousePermission('hr', $employeeTransferDetail->employee->user->warehouse_id)) {
                return back()->with('failedMessage', 'You do not have permission to approve this employee transfer request.');
            }
        }

        [$isExecuted, $message] = $this->employeeTransferService->approve($employeeTransfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermission('Read Employee Transfer', $employeeTransfer->createdBy),
            new EmployeeTransferApproved($employeeTransfer)
        );

        return back()->with('successMessage', $message);
    }
}
