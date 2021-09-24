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
        $routeCachingResponse = Artisan::call('route:cache') == 0 ? 'ROUTE CACHING COMPLETED' : 'ROUTE CACHING FAILED';
        $viewCachingResponse = Artisan::call('view:cache') == 0 ? 'VIEW CACHING COMPLETED' : 'VIEW CACHING FAILED';
        $configCachingResponse = Artisan::call('config:cache') == 0 ? 'CONFIG CACHING COMPLETED' : 'CONFIG CACHING FAILED';
        $optimizeResponse = Artisan::call('optimize') == 0 ? 'OPTIMIZE COMPLETED' : 'OPTIMIZE FAILED';

        $this->info("{$routeCachingResponse}\n{$viewCachingResponse}\n{$configCachingResponse}\n{$optimizeResponse}");

        $shellMessage = shell_exec('composer dump-autoload -o');

        echo $shellMessage;

        return 0;
    }
}
