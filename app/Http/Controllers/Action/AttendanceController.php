<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\CancelTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Attendance;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Attendance Management');
    }

    public function approve(Attendance $attendance, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $attendance);

        if (!authUser()->hasWarehousePermission('hr', $attendance->warehouse_id)) {
            return back()->with('failedMessage', 'You do not have permission to approve this attendance request.');
        }

        if (!$attendance->attendanceEnded()) {
            return back()->with('failedMessage', 'You can not approve an attendance with ending period after today.');
        }

        [$isExecuted, $message] = $action->execute($attendance);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function cancel(Attendance $attendance, CancelTransactionAction $action)
    {
        $this->authorize('cancel', $attendance);

        [$isExecuted, $message] = $action->execute($attendance);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}
