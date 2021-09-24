<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class EnablePlanFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'plan-feature:enable {featureName} {planName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enable feature for a specific plan';

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
        try {
            Feature::enableOrDisableForPlan(
                $this->argument('featureName'),
                $this->argument('planName'),
                1
            );

            $this->info('Feature enabled for this plan.');
        } catch (\Throwable $th) {
            $this->error('Feature name or plan name is wrong.');
        }

        return 0;
    }
}
