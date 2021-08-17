<?php

namespace App\Console\Commands;

use App\User;
use App\Models\Company;
use App\Models\Employee;
use App\Models\ProformaInvoice;
use App\Notifications\ProformaInvoiceExpirationIsClose;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;

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

            $notifiableUsersId = User::role(['Sales Officer', 'System Manager'])->get()->pluck('id')->toArray();

            $users = Employee::with('user')
                ->where('company_id', $company->id)
                ->whereIn('user_id', $notifiableUsersId)
                ->get()
                ->pluck('user');

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new ProformaInvoiceExpirationIsClose($proformaInvoices));

        }

        return 0;
    }
}
