<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class DisablePlanFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan-feature:disable {featureName} {planName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable feature for a specific plan';

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
        Feature::disableForPlan($this->argument('featureName'), $this->argument('planName'));

        $this->info('Feature disabled for this plan');

        return 0;
    }
}
