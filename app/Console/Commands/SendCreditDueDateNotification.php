<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Credit;
use App\Models\User;
use App\Notifications\CreditDueDateIsClose;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

class SendCreditDueDateNotification extends Command
{
    protected $signature = 'credit:due-date-notification';

    protected $description = 'Send credit due date notification';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $companies = Company::enabled()->get();

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $totalCredits = Credit::query()
                ->where('company_id', $company->id)
                ->whereRaw('DATEDIFF(due_date, CURRENT_DATE) BETWEEN 1 AND 5')
                ->count();

            if ($totalCredits == 0) {
                continue;
            }

            $users = User::query()
                ->role(['System Manager', 'Analyst', 'Sales Officer'])
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new CreditDueDateIsClose($totalCredits));
        }

        return 0;
    }
}
