<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RemovePendingPayment extends Command
{
    protected $signature = 'remove:removeOlderPendingPayments';

    protected $description = 'Remove pending and failed payments';

    public function handle()
    {
        $expiredPayments = DB::table('payment_transactions')
            ->where('status', 'pending')
            ->where(function($query) {
                $query->where('payment_method', 'Telebirr')->orWhere('payment_method', 'Arifpay');
            })
            ->where('created_at', '<', now()->subDays(1)->subHours(12))
            ->delete();

        $this->info("Removed $expiredPayments expired pending payments.");
    }
}
