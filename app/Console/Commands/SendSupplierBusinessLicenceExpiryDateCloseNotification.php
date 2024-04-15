<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Supplier;
use App\Models\User;
use App\Notifications\SupplierBusinessLicenceExpiryDateClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendSupplierBusinessLicenceExpiryDateCloseNotification extends Command
{
    protected $signature = 'supplier:licence-expiry-date-close-notification';

    protected $description = 'Send supplier business licence expiration date is close notifications';

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
            $suppliers = Supplier::query()
                ->where('company_id', $company->id)
                ->whereRaw('DATEDIFF(business_license_expires_on, CURRENT_DATE) <= 30')
                ->get(['id']);

            if ($suppliers->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Supplier')
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new SupplierBusinessLicenceExpiryDateClose($suppliers));
        }

        return 0;
    }
}
