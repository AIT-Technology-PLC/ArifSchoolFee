<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunDbBackup extends Command
{
    protected $signature = 'backup:db';

    protected $description = 'Run spatie backup:run for database only';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Artisan::call('backup:run --only-db --disable-notifications');

        return 0;
    }
}
