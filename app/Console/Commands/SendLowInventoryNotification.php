<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\User;
use App\Notifications\LowProductInventoryLevel;
use App\Services\Inventory\MerchandiseProductService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendLowInventoryNotification extends Command
{
    protected $signature = 'inventory:low-level-notification';

    protected $description = 'Send low level inventory product notification';

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
            $totalLimitedProducts = (new MerchandiseProductService)
                ->getLimitedMerchandiseProductsQuery()
                ->where('company_id', $company->id)
                ->count();

            if ($totalLimitedProducts == 0) {
                continue;
            }

            $users = User::query()
                ->permission('Read Available Inventory')
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new LowProductInventoryLevel($totalLimitedProducts));
        }

        return 0;
    }
}
