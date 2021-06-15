<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class DisableFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:disable {featureName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable a feature';

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
        Feature::disable($this->argument('featureName'));

        $this->info('Feature is disabled successfully');

        return 0;
    }
}
