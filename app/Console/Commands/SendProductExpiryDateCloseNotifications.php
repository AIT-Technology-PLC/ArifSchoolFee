<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\MerchandiseBatch;
use App\Models\User;
use App\Notifications\ProductExpiryDateClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendProductExpiryDateCloseNotifications extends Command
{
    protected $signature = 'product:expiry-date-close-notification';

    protected $description = 'Send product batch expiration date is close notifications';

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
            $merchandiseBatches = MerchandiseBatch::query()
                ->whereRelation('merchandise', 'company_id', $company->id)
                ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
                ->join('companies', 'merchandises.company_id', '=', 'companies.id')
                ->whereRaw('CASE
                        WHEN companies.expiry_time_type = "Days" THEN DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in
                        WHEN companies.expiry_time_type = "Months" THEN DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in*30
                        ELSE DATEDIFF(expiry_date, CURRENT_DATE) = companies.expired_in*365
                    END')
                ->get();

            if ($merchandiseBatches->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Expired Inventory')
                ->where(function ($query) use ($merchandiseBatches) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', $merchandiseBatches->pluck('warehouse_id'));
                })
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new ProductExpiryDateClose($merchandiseBatches, $company));
        }

        return 0;
    }
}