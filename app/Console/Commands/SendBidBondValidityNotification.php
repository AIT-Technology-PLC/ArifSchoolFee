<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Tender;
use App\Models\User;
use App\Notifications\BidBondDeadlineIsClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendBidBondValidityNotification extends Command
{
    protected $signature = 'tender:bid-bond-validity-notification';

    protected $description = 'Send bid bond validity notification when 5 days is left';

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

            $tenders = Tender::query()
                ->where('company_id', $company->id)
                ->whereNotNull('bid_bond_validity')
                ->whereRaw('DATEDIFF(DATE_ADD(closing_date, INTERVAL bid_bond_validity DAY), CURRENT_DATE) BETWEEN 1 AND 5')
                ->get();

            if ($tenders->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->role(['System Manager', 'Analyst', 'Tender Officer'])
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new BidBondDeadlineIsClose($tenders->count()));
        }

        return 0;
    }
}
