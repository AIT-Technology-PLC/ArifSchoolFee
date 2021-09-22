<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Employee;
use App\Models\Tender;
use App\Notifications\TenderDeadlineIsClose;
use App\Models\User;
use Illuminate\Console\Command;
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

            $notifiableUsersId = User::role(['Tender Officer', 'System Manager'])->get()->pluck('id')->toArray();

            $users = Employee::with('user')
                ->where('company_id', $company->id)
                ->whereIn('user_id', $notifiableUsersId)
                ->get()
                ->pluck('user');

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new TenderDeadlineIsClose($tenders));

        }

        return 0;
    }
}
