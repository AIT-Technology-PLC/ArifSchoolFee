<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Feature;
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
        $leaveManagementFeature = Feature::firstWhere('name', 'Leave Management');

        $companies = Company::enabled()
            ->where('income_tax_region', 'Ethiopia')
            ->where('paid_time_off_type', 'Days')
            ->get();

        if ($companies->isEmpty()) {
            return 0;
        }

        DB::transaction(function () use ($companies, $leaveManagementFeature) {
            foreach ($companies as $company) {
                $isEnabledForCompany = $company->features()->wherePivot('feature_id', $leaveManagementFeature->id)->wherePivot('is_enabled', 1)->exists();
                $isEnabledForPlan = $company->plan->features()->wherePivot('feature_id', $leaveManagementFeature->id)->wherePivot('is_enabled', 1)->exists();

                if (!$isEnabledForCompany && !$isEnabledForPlan) {
                    continue;
                }

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
