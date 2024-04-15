<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Debt;
use App\Models\User;
use App\Notifications\DebtDueDateIsClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendDebtDueDateNotification extends Command
{
    protected $signature = 'debt:due-date-notification';

    protected $description = 'Send debt due date notification';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $companies = Company::enabled()->get(['id']);

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $totalDebts = Debt::query()
                ->where('company_id', $company->id)
                ->whereColumn('debt_amount', '>', 'debt_amount_settled')
                ->whereRaw('DATEDIFF(due_date, CURRENT_DATE) BETWEEN 1 AND 7')
                ->count();

            if ($totalDebts == 0) {
                continue;
            }

            $users = User::query()
                ->permission('Read Debt')
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new DebtDueDateIsClose($totalDebts));
        }

        return 0;
    }
}
