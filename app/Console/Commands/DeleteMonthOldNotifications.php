<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Notifications\DatabaseNotification;

class DeleteMonthOldNotifications extends Command
{
    protected $signature = 'notifications:delete-month';

    protected $description = 'Delete notifications that were created month ago or more';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(DatabaseNotification $databaseNotification)
    {
        $databaseNotification->where('created_at', '<=', now()->subDays(30))->delete();

        return 0;
    }
}
