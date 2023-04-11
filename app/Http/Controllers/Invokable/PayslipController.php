<?php

namespace App\Http\Controllers\Invokable;

use App\Actions\ProcessPayrollAction;
use App\Http\Controllers\Controller;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\Payroll;
use Barryvdh\DomPDF\Facade\Pdf;

class PayslipController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Payroll Management');
    }

    public function __invoke(Payroll $payroll, Employee $employee, ProcessPayrollAction $processPayrollAction)
    {
        $this->authorize('view', $payroll);

        $this->authorize('view', $employee);

        if (!$payroll->isPaid()) {
            return back()->with('failedMessage', 'You can not print payslip for a payroll that is not paid yet.');
        }

        $payslip = $processPayrollAction->execute($payroll)->firstWhere('employee_id', $employee->id);

        $compensations = Compensation::find($payroll->payrollDetails()->pluck('compensation_id'));

        return Pdf::loadView('payslips.print', compact('payslip', 'compensations'))->stream();
    }
}
