<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class DisableCompanyFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company-feature:disable {featureName} {companyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable feature for a specific company';

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
        $isDisabled = Feature::disableForCompany($this->argument('featureName'), $this->argument('companyId'));

        $this->info('Feature disabled.');

        return 0;
    }
}
