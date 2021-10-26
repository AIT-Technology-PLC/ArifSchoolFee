<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\TenderDeadlineIsClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendTenderDeadlineNotifications extends Command
{
    protected $signature = 'tender:daily-deadline-notification';

    protected $description = 'Send tender deadline notifications to tender officers and system managers';

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

            $tenders = Tender::where('company_id', $company->id)
                ->whereRaw('DATEDIFF(closing_date, CURRENT_DATE) BETWEEN 1 AND 5')
                ->get();

            if ($tenders->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->role(['System Manager', 'Analyst', 'Tender Officer'])
                ->where(function ($query) use ($tenders) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', $tenders->pluck('warehouse_id'));
                })
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new TenderDeadlineIsClose($tenders));

        }

        return 0;
    }
}
