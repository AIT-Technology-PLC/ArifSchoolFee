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
        $isSuccess = Artisan::call('backup:run --only-db --disable-notifications');

        $isSuccess == 0 ? $this->info('Backup completed successfully.') : $this->error('Backup failed.');

        return 0;
    }
}
