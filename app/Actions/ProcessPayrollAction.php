<?php

namespace App\Actions;

use App\Models\AttendanceDetail;
use App\Models\Compensation;
use App\Models\Employee;
use App\Utilities\IncomeTaxCalculator;

class ProcessPayrollAction
{
    private $payrollSheet;

    private $payroll;

    private $employeesCompensations;

    private $payrollDetails;

    private $employees;

    private $attendanceDetails;

    public function execute($payroll)
    {
        $this->setup($payroll);

        $this->generateCompensations();

        $this->generatePayrollSheet();

        return $this->payrollSheet;
    }

    private function setup($payroll)
    {
        $this->payroll = $payroll;

        $this->employeesCompensations = collect();

        $this->payrollDetails = $this->payroll->payrollDetails()->with(['employee', 'compensation'])->get();

        $this->employees = $this->payrollDetails->pluck('employee')->unique();

        $this->attendanceDetails = AttendanceDetail::query()
            ->whereHas('attendance', function ($query) {
                return $query->approved()
                    ->where('starting_period', $this->payroll->starting_period)
                    ->where('ending_period', $this->payroll->ending_period);
            })
            ->whereIn('employee_id', $this->employees->pluck('id'))
            ->get(['employee_id', 'days']);
    }

    private function generateCompensations()
    {
        foreach ($this->employees as $employee) {
            foreach ($this->payrollDetails->where('employee_id', $employee->id) as $payrollDetail) {
                $this->employeesCompensations->push([
                    'employee' => $payrollDetail->employee,
                    'compensation' => $payrollDetail->compensation,
                    'employee_id' => $payrollDetail->employee_id,
                    'compensation_id' => $payrollDetail->compensation_id,
                    'compensation_name' => $payrollDetail->compensation->name,
                    'compensation_is_taxable' => $payrollDetail->compensation->is_taxable,
                    'compensation_type' => $payrollDetail->compensation->type,
                    'amount' => $payrollDetail->amount,
                ]);
            }
        }
    }

    private function generatePayrollSheet()
    {
        $this->payrollSheet = $this->employees->map(function ($employee) {
            $employeeCompensations = $this->employeesCompensations->where('employee_id', $employee->id);

            $data['employee'] = $employee;
            $data['employee_id'] = $employee->id;
            $data['gross_salary'] = $employeeCompensations->where('compensation_type', 'earning')->sum('amount');
            $data['taxable_income'] = $employeeCompensations->where('compensation_type', 'earning')->where('compensation_is_taxable', 1)->sum('amount');
            $data['income_tax'] = IncomeTaxCalculator::calculate($data['taxable_income'])['tax_amount'];
            $data['deductions'] = $employeeCompensations->where('compensation_type', 'deduction')->sum('amount') + $data['income_tax'];
            $data['net_payable'] = $data['gross_salary'] - $data['deductions'];
            $data['absence_days'] = $this->attendanceDetails->where('employee_id', $employee->id)->first()->days ?? 0;
            $data['absence_deduction'] = $data['net_payable'] / userCompany()->working_days * $data['absence_days'];
            $data['net_payable_after_absenteeism'] = $data['net_payable'] - $data['absence_deduction'];

            return $employeeCompensations->pluck('amount', 'compensation_name')->toArray() + $data;
        });
    }
}
