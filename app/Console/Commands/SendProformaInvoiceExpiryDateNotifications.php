<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Company;
use App\Models\ProformaInvoice;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;
use App\Notifications\ProformaInvoiceExpirationIsClose;

class SendProformaInvoiceExpiryDateNotifications extends Command
{
    protected $signature = 'proforma-invoice:expiry-date-notification';

    protected $description = 'Send proforma invoice expiration notifications to sales officers and system managers';

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

            $proformaInvoices = ProformaInvoice::where('company_id', $company->id)
                ->whereRaw('DATEDIFF(expires_on, CURRENT_DATE) BETWEEN 1 AND 5')
                ->get();

            if ($proformaInvoices->isEmpty()) {
                continue;
            }

            $users = User::role(['Sales Officer', 'System Manager'])
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new ProformaInvoiceExpirationIsClose($proformaInvoices));

        }

        return 0;
    }
}
