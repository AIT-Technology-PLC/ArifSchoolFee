<?php

namespace App\Console\Commands;

use App\Mail\SalesReport;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class DispatchSendingSalesReportEmail extends Command
{
    protected $signature = 'email:sales-report {--monthly}';

    protected $description = 'Send daily (default) or monthly sales report to users.';

    public function handle()
    {
        $companies = Company::enabled()->whereRelation('employees', 'does_receive_sales_report_email', 1)->get();
        $period = [today()->yesterday(), today()->yesterday()];

        if ($this->option('monthly')) {
            $period = [today()->subMonth()->startOfMonth(), today()->subMonth()->endOfMonth()];
        }

        foreach ($companies as $company) {
            $employees = $company->employees()->with('user:id,name,email')->salesReportEmailRecipent()->get();

            foreach ($employees as $employee) {
                Mail::to($employee->user)->send(new SalesReport($employee->user, $period));
            }
        }
    }
}
