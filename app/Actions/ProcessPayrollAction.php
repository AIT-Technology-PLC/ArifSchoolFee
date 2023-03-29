<?php

namespace App\Actions;

use App\Models\AttendanceDetail;
use App\Models\Payroll;
use App\Utilities\IncomeTaxCalculator;

class ProcessPayrollAction
{
    private $payrollSheet;

    private $payroll;

    private $employeesCompensations;

    private $employees;

    private $attendanceDetails;

    private $workingDays;

    public function execute($payroll)
    {
        $this->setup($payroll);

        $this->generateCompensations();

        $this->generatePayrollSheet();

        return $this->payrollSheet;
    }

    private function setup($payroll)
    {
        $this->workingDays = $payroll->working_days ?? userCompany()->working_days;

        $this->payroll = $payroll->load('payrollDetails.employee');

        $this->employees = $this->payroll->payrollDetails->pluck('employee')->unique();

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
        $this->employeesCompensations = Payroll::query()
            ->join('payroll_details', 'payrolls.id', '=', 'payroll_details.payroll_id')
            ->join('employees', 'payroll_details.employee_id', '=', 'employees.id')
            ->join('users', 'employees.user_id', '=', 'users.id')
            ->join('compensations', 'payroll_details.compensation_id', '=', 'compensations.id')
            ->where('payrolls.id', $this->payroll->id)
            ->select([
                'payroll_details.employee_id',
                'payroll_details.compensation_id',
                'compensations.name AS compensation_name',
                'compensations.is_taxable AS compensation_is_taxable',
                'compensations.type AS compensation_type',
                'users.name',
                'payroll_details.amount',
            ])
            ->get();
    }

    private function generatePayrollSheet()
    {
        $this->payrollSheet = $this->employees->map(function ($employee) {
            $employeeCompensations = $this->employeesCompensations->where('employee_id', $employee->id);

            $data['employee'] = $employee;
            $data['payroll'] = $this->payroll;
            $data['employee_id'] = $employee->id;
            $data['employee_name'] = $employee->user->name;
            $data['gross_salary'] = $employeeCompensations->where('compensation_type', 'earning')->sum('amount');
            $data['taxable_income'] = $employeeCompensations->where('compensation_type', 'earning')->where('compensation_is_taxable', 1)->sum('amount');
            $data['income_tax'] = IncomeTaxCalculator::calculate($data['taxable_income'])['tax_amount'];
            $data['deductions'] = $employeeCompensations->where('compensation_type', 'deduction')->sum('amount') + $data['income_tax'];
            $data['net_payable'] = $data['gross_salary'] - $data['deductions'];
            $data['working_days'] = $this->workingDays - ($this->attendanceDetails->where('employee_id', $employee->id)->first()->days ?? 0);
            $data['absence_days'] = $this->attendanceDetails->where('employee_id', $employee->id)->first()->days ?? 0;

            if (!userCompany()->isBasicSalaryAfterAbsenceDeduction()) {
                $data['absence_deduction'] = $data['net_payable'] / $this->workingDays * $data['absence_days'];
                $data['net_payable_after_absenteeism'] = $data['net_payable'] - $data['absence_deduction'];
            }

            return $employeeCompensations->pluck('amount', 'compensation_name')->toArray() + $data;
        });
    }
}
