<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\ProformaInvoice;
use App\Models\User;
use App\Notifications\ExpiredProformaInvoicesCancelled;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CancelExpiredProformaInvoices extends Command
{
    protected $signature = 'cancel:expired-proforma-invoices';

    protected $description = 'Cancel expired proforma invoices';

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

        DB::transaction(function () use ($companies) {
            foreach ($companies as $company) {
                $proformaInvoices = ProformaInvoice::query()
                    ->where('company_id', $company->id)
                    ->pending()
                    ->notConfirmed()
                    ->expired()
                    ->get();

                if ($proformaInvoices->count() == 0) {
                    continue;
                }

                $proformaInvoices->each->cancel();

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

                Notification::send($users, new ExpiredProformaInvoicesCancelled($proformaInvoices->count()));
            }
        });

        return 0;
    }
}
