<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeactivateExpiredSubscriptionCompanies extends Command
{
    protected $signature = 'company:deactivate';

    protected $description = 'Deactivate expired subscription company accounts.';

    public function handle()
    {
        $companies = Company::expiredSubscriptions()->get();

        DB::transaction(function () use ($companies) {
            foreach ($companies as $company) {
                $company->deactivate();
            }
        });

        $this->info($companies->count() . ' companies have been deactivated.');
    }
}
