<?php

namespace App\Http\Controllers\Action;

use App\Actions\ProcessPayrollAction;
use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Notifications\PayrollPaid;
use App\Services\Models\PayrollService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
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

        [$isExecuted, $message] = $this->payrollService->pay($payroll);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermission('Read Payroll', $payroll->createdBy),
            new PayrollPaid($payroll)
        );

        return back()->with('successMessage', $message);
    }

    public function printed(Payroll $payroll, ProcessPayrollAction $action)
    {
        $this->authorize('view', $payroll);

        if (!$payroll->isApproved()) {
            return back()->with('failedMessage', 'This Payroll is not approved yet.');
        }

        $employees = $action->execute($payroll);

        return Pdf::loadView('payrolls.print', compact('payroll', 'employees'))->stream();
    }
}