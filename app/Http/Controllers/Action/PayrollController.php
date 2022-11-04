<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Notifications\PayrollPaid;
use App\Services\Models\PayrollService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class PayrollController extends Controller
{
    private $payrollService;

    public function __construct(PayrollService $payrollService)
    {
        $this->middleware('isFeatureAccessible:Payroll Management');

        $this->payrollService = $payrollService;
    }

    public function approve(Payroll $payroll)
    {
        $this->authorize('approve', $payroll);

        [$isExecuted, $message] = $this->payrollService->approve($payroll);

        return back()->with('successMessage', $message);
    }

    public function pay(Payroll $payroll)
    {
        $this->authorize('pay', $payroll);

        if (!$payroll->isApproved()) {
            return back()->with('failedMessage', 'You can not pay a payroll that is not approved.');
        }

        if ($payroll->isPaid()) {
            return back()->with('failedMessage', 'This transaction is already paid.');
        }

        $payroll->pay();

        Notification::send(
            Notifiables::byPermission('Read Payroll', $payroll->createdBy),
            new PayrollPaid($payroll)
        );

        return back()->with('successMessage', 'You have paid this transaction successfully.');
    }
}
