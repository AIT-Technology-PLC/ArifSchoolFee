<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class IncrementEmployeePaidTimeOffAmount extends Command
{
    protected $signature = 'increment:increment-employee-paid-time-off-amount';

    protected $description = 'Increment employee paid time off amount';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $companies = Company::enabled()
            ->where('income_tax_region', 'Ethiopia')
            ->where('paid_time_off_type', 'Days')
            ->whereRelation(
                'features',
                function ($query) {
                    $query->where('features.name', 'Leave Management')
                        ->where('features.is_enabled', '1');
                }
            )->get();

        if ($companies->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($companies) {
            foreach ($companies as $company) {
                $employees = $company->employees()
                    ->enabled()
                    ->whereNotNull('date_of_hiring')
                    ->whereDay('date_of_hiring', '=', today())
                    ->get();

                if ($employees->count() == 0) {
                    continue;
                }

                foreach ($employees as $employee) {
                    $incrementAmount = 1.34;
                    $yearsOfEmployeeWorked = now()->diffInYears($employee->date_of_hiring);

                    if ($yearsOfEmployeeWorked >= 2) {
                        $incrementAmount = (($yearsOfEmployeeWorked / 2) * 0.08) + 1.34;
                    }

                    $employee->incrementPaidTimeOffAmount($incrementAmount);
                }
            }
        });

        return 0;
    }
}