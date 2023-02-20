<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DailyRunCommands extends Command
{
    protected $signature = 'routine:daily';

    protected $description = 'Commands that run once per day';

    private $commands = [
        'cancel:expired-proforma-invoices',
        'cancel:expired-reservations',
        'notifications:delete-week',
        'tender:bid-bond-validity-notification',
        'inventory:low-level-notification',
        'proforma-invoice:expiry-date-notification',
        'reservation:expiry-date-notification',
        'tender:daily-deadline-notification',
        'credit:due-date-notification',
        'backup:run --disable-notifications',
        'job:behind-schedule-notification',
        'debt:due-date-notification',
        'product:expiry-date-close-notification',
        'supplier:licence-expiry-date-close-notification',
        'customer:licence-expiry-date-close-notification',
        'increment:increment-employee-paid-time-off-amount',
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        foreach ($this->commands as $command) {
            Artisan::call($command);
        }

        return 0;
    }
}