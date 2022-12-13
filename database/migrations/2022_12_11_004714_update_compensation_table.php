<?php

use App\Models\Company;
use App\Models\Compensation;
use App\Models\Payroll;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('compensations', function (Blueprint $table) {
            $table->dropUnique(['name', 'company_id']);
        });

        $companies = Company::enabled()->get();

        foreach ($companies as $company) {
            $payrolls = Payroll::approved()->where('company_id', $company->id)->with('payrollDetails')->get();

            DB::transaction(function () use ($company, $payrolls) {
                $payrolls->each(function ($payroll) use ($company) {
                    $this->storeDerivedCompensations($payroll, $company);
                });
            });
        }
    }

    private function storeDerivedCompensations($payroll, $company)
    {
        $employees = $payroll->payrollDetails->pluck('employee')->unique();

        $data = collect();

        $derivedCompensations = Compensation::active()->derived()->where('company_id', $company->id)->orderBy('id', 'DESC')->get();

        foreach ($employees as $employee) {
            foreach ($derivedCompensations as $compensation) {
                if ($payroll->payrollDetails->where('employee_id', $employee->id)->where('compensation_id', $compensation->id)->count()) {
                    $derivedAmount = $payroll->payrollDetails->where('employee_id', $employee->id)->where('compensation_id', $compensation->id)->first()->amount;
                } else {
                    $derivedAmount = ($payroll->payrollDetails
                            ->where('employee_id', $employee->id)
                            ->where('compensation_id', $compensation->depends_on)
                            ->first()['amount'] ?? 0) * ($compensation->percentage / 100);
                }

                $data->push([
                    'employee_id' => $employee->id,
                    'compensation_id' => $compensation->id,
                    'amount' => !is_null($compensation->maximum_amount) && $derivedAmount >= $compensation->maximum_amount ? $compensation->maximum_amount : $derivedAmount,
                ]);
            }
        }

        $payroll->payrollDetails()->createMany($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('compensations', function (Blueprint $table) {
            $table->unique(['name', 'company_id']);
        });
    }
};
