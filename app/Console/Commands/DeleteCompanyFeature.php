<?php

namespace App\Console\Commands;

use App\Models\Feature;
use Illuminate\Console\Command;

class DeleteCompanyFeature extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'company-feature:delete {featureName} {companyId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete additional feature of a company';

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
        Feature::deleteForCompany($this->argument('featureName'), $this->argument('companyId'));

        $this->info('Feature delete from a company');

        return 0;
    }
}
