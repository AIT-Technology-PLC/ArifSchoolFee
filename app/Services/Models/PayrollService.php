<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\ProcessPayrollAction;
use App\Models\Compensation;
use App\Models\CompensationAdjustmentDetail;
use App\Models\EmployeeCompensation;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Notifications\PayrollApproved;
use Illuminate\Support\Facades\DB;

class PayrollService
{
    public function approve($payroll)
    {
        return DB::transaction(function () use ($payroll) {
            if ($payroll->isPaid()) {
                return back()->with('failedMessage', 'This Payroll is paid.');
            }

            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($payroll, PayrollApproved::class, 'Pay Payroll');

            $payroll->payrollDetails()->forceDelete();

            $employeeCompensations = EmployeeCompensation::whereIn('compensation_id', Compensation::active()->pluck('id'))->with('employee')->get(['employee_id', 'compensation_id', 'amount']);

            $compensationAdjustments = CompensationAdjustmentDetail::whereHas('compensationAdjustment', function ($query) use ($payroll) {
                return $query->approved()
                    ->where('starting_period', $payroll->starting_period)
                    ->where('ending_period', $payroll->ending_period);
            })->get(['employee_id', 'compensation_id', 'amount']);

            $employeeCompensations = $employeeCompensations
                ->whereNotIn('employee_id', $compensationAdjustments->pluck('employee_id'))
                ->whereNotIn('compensation_id', $compensationAdjustments->pluck('employee_id'))
                ->push(...$compensationAdjustments)
                ->toArray();

            $payroll->payrollDetails()->createMany($employeeCompensations);

            $this->storeDerivedCompensations($payroll, $compensationAdjustments);

            return [true, $message];
        });
    }

    public function pay($payroll)
    {
        if ($payroll->isPaid()) {
            return [false, 'You can not pay a payroll that is already paid.'];
        }

        if (!$payroll->isApproved()) {
            return [false, 'You can not pay a payroll that is not approved.'];
        }

        DB::transaction(function () use ($payroll) {
            $payroll->pay();

            $expenseCategory = ExpenseCategory::firstOrCreate([
                'name' => 'Salary Expenses',
            ]);

            $expense = Expense::create([
                'code' => nextReferenceNumber('expenses'),
                'expense_category_id' => $expenseCategory,
                'reference_number' => $payroll->code,
                'tax_type' => 'None',
                'issued_on' => now(),
            ]);

            $expense->expenseDetails()->createMany([
                [
                    'expense_id' => $expense->id,
                    'name' => 'Salary Expenses',
                    'expense_category_id' => $expenseCategory->id,
                    'quantity' => 1,
                    'unit_price' => (new ProcessPayrollAction)->execute($payroll)->sum('gross_salary'),
                ],
            ]);

            $expense->approve();
        });

        return [true, 'You have paid this transaction successfully.'];
    }

    private function storeDerivedCompensations($payroll, $compensationAdjustments)
    {
        $employees = $payroll->payrollDetails->pluck('employee')->unique();

        $data = collect();

        $derivedCompensations = Compensation::active()->derived()->orderBy('id', 'DESC')->get();

        foreach ($employees as $employee) {
            foreach ($derivedCompensations as $compensation) {
                if ($payroll->payrollDetails->where('employee_id', $employee->id)->where('compensation_id', $compensation->id)->count()) {
                    $derivedAmount = $payroll->payrollDetails->where('employee_id', $employee->id)->where('compensation_id', $compensation->id)->first()->amount;
                } else {
                    $derivedAmount = $payroll->payrollDetails
                        ->where('employee_id', $employee->id)
                        ->where('compensation_id', $compensation->depends_on)
                        ->first()['amount'] * ($compensation->percentage / 100);
                }

                $data->push([
                    'employee_id' => $employee->id,
                    'compensation_id' => $compensation->id,
                    'amount' => !is_null($compensation->maximum_amount) && $derivedAmount >= $compensation->maximum_amount ? $compensation->maximum_amount : $derivedAmount,
                ]);
            }
        }

        $payroll->payrollDetails()->createMany(
            $data
                ->whereNotIn('employee_id', $compensationAdjustments->pluck('employee_id'))
                ->whereNotIn('compensation_id', $compensationAdjustments->pluck('employee_id'))
                ->toArray()
        );
    }
}
