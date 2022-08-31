<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Notifications\PayrollApproved;
use App\Notifications\PayrollPaid;
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

    public function pay(Payroll $payroll)
    {
        $this->authorize('pay', $payroll);

        if (!$payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not pay a payroll that is not approved.');
        }

        if ($payroll->isPaid()) {
            return [false, 'This transaction is already paid.'];
        }

        $payroll->pay();

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Payroll', $payroll->warehouse_id, $payroll->createdBy),
            new PayrollPaid($payroll)
        );

        return back()->with('successMessage', 'You have paid this transaction successfully.');
    }
}