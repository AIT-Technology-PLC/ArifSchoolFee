<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\PayTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Notifications\PayrollApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class PayrollController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Payroll Management');
    }

    public function approve(Payroll $payroll, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $payroll);

        if (!$payroll->payrollEnded()) {
            return back()->with('failedMessage', 'You can not approve a payroll with ending period after today.');
        }

        [$isExecuted, $message] = $action->execute($payroll);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Payroll', $payroll->warehouse_id, $payroll->createdBy),
            new PayrollApproved($payroll)
        );

        return back()->with('successMessage', $message);
    }

    public function pay(Payroll $payroll, PayTransactionAction $action)
    {
        $this->authorize('pay', $payroll);

        if (!$payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not pay a payroll that is not approved.');
        }

        [$isExecuted, $message] = $action->execute($payroll);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}