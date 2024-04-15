<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\ProformaInvoice;
use App\Models\User;
use App\Notifications\ProformaInvoiceExpirationIsClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
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
        $companies = Company::enabled()->get(['id']);

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $proformaInvoices = ProformaInvoice::query()
                ->where('company_id', $company->id)
                ->pending()
                ->whereRaw('DATEDIFF(expires_on, CURRENT_DATE) BETWEEN 1 AND 5')
                ->get(['id', 'warehouse_id']);

            if ($proformaInvoices->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Proforma Invoice')
                ->where(function ($query) use ($proformaInvoices) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', $proformaInvoices->pluck('warehouse_id'));
                })
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
