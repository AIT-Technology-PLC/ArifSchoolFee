<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DailyRunCommands extends Command
{
    protected $signature = 'routine:daily';

    protected $description = 'Commands that run once per day';

    private $commands = [
        'notifications:delete-month',
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