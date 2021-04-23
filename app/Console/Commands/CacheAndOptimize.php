<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class CacheAndOptimize extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cache views, routes, configs, optimize bootstrap files and composer autoload optimize';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Artisan::call('route:cache');
        Artisan::call('view:cache');
        Artisan::call('config:cache');
        Artisan::call('optimize');

        $this->info('All the commands were executed successfully');

        $shellMessage = shell_exec('composer dump-autoload -o');

        echo $shellMessage;

        return 0;
    }
}
