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
    protected $signature = 'optimize:cache';

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
        $viewCachingResponse = Artisan::call('view:cache') == 0 ? 'VIEW CACHING COMPLETED' : 'VIEW CACHING FAILED';

        $optimizeResponse = Artisan::call('optimize') == 0 ? 'CONFIG AND ROUTES CACHING COMPLETED' : 'CONFIG AND ROUTES CACHING FAILED';

        $packageDiscoveryResponse = Artisan::call('package:discover') == 0 ? 'PACKAGE MANIFEST GENERATED SUCCESSFULLY' : 'PACKAGE DISCOVERY FAILED';

        $this->info("{$viewCachingResponse}\n{$optimizeResponse}\n{$packageDiscoveryResponse}");

        $shellMessage = shell_exec('composer dump-autoload -o');

        echo $shellMessage;

        return 0;
    }
}
