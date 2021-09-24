<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class ShowFeatureStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feature:status {featureName}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Show status of a feature';

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
            $status = Feature::status($this->argument('featureName'));

            $this->info('This feature is ' . $status);
        } catch (\Throwable $th) {
            $this->error('Feature name is wrong.');
        }

        return 0;
    }
}
