<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Merchandise;
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
        $companies = Company::enabled()->get(['id', 'expiry_in_days']);

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $merchandiseBatches = MerchandiseBatch::query()
                ->available()
                ->whereRelation('merchandise', 'company_id', $company->id)
                ->whereRaw('DATEDIFF(expires_on, CURRENT_DATE) <= ' . (int) $company->expiry_in_days)
                ->get();

            if ($merchandiseBatches->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Expired Inventory')
                ->where(function ($query) use ($merchandiseBatches) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', Merchandise::whereIn('id', $merchandiseBatches->pluck('merchandise_id'))->pluck('warehouse_id'));
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
