<?php

namespace App\Utilities;

use App\Models\Compensation;
use App\Models\Employee;

class PayrollGenerator
{
    public static function calculate($employee)
    {
        $compensations = Employee::query()
            ->join('employee_compensations', 'employees.id', '=', 'employee_compensations.employee_id')
            ->join('compensations', 'employee_compensations.compensation_id', '=', 'compensations.id')
            ->where('employees.id', $employee->id)
            ->select([
                'compensations.id AS compensation_id',
                'compensations.name AS name',
                'compensations.is_taxable AS is_taxable',
                'compensations.type AS type',
                'employee_compensations.amount AS amount',
            ])
            ->get();

        $derivedCompensations = Compensation::active()->derived()->whereNotIn('id', $compensations->pluck('compensation_id'))->orderBy('id', 'DESC')->get();

        foreach ($derivedCompensations as $compensation) {
            $derivedAmount = ($compensations->where('compensation_id', $compensation->depends_on)->first()['amount'] ?? 0) * ($compensation->percentage / 100);

            $compensations->push([
                'name' => $compensation->name,
                'is_taxable' => $compensation->is_taxable,
                'type' => $compensation->type,
                'amount' => !is_null($compensation->maximum_amount) && $derivedAmount >= $compensation->maximum_amount ? $compensation->maximum_amount : $derivedAmount,
            ]);
        }

        $data['gross_salary'] = $compensations->where('type', 'earning')->sum('amount');
        $data['taxable_income'] = $compensations->where('type', 'earning')->where('is_taxable', 1)->sum('amount');
        $data['income_tax'] = IncomeTaxCalculator::calculate($data['taxable_income'])['tax_amount'];
        $data['deductions'] = $compensations->where('type', 'deduction')->sum('amount') + $data['income_tax'];
        $data['net_payable'] = $data['gross_salary'] - $data['deductions'];

        return $compensations->pluck('amount', 'name')->toArray() + $data;
    }
}
