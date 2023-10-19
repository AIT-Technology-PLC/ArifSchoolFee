<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class StagingDeployRoutine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:staging {withDowntime=1}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy changes from onrica/smartwork repo dev branch';

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
        if (env('APP_ENV') != 'staging') {
            $this->error('This command is only used in staging environment.');
            return 0;
        }

        if ($this->argument('withDowntime')) {
            Artisan::call('down');
        }

        $githubData = env('GITHUB_USERNAME') . ':' . env('GITHUB_PASSWORD');

        $this->info(exec('git pull https://' . $githubData . '@github.com/onrica/smartwork.git dev'));

        $this->newLine();

        $this->info(exec('composer install --no-dev'));

        $this->newLine();

        Artisan::call('migrate --force');

        Artisan::call('db:seed Plans --force');
        Artisan::call('db:seed Limits --force');
        Artisan::call('db:seed Features --force');
        Artisan::call('db:seed Integrations --force');
        Artisan::call('db:seed Permissions --force');
        Artisan::call('db:seed CreateOrReplaceSalesReportViews --force');

        Artisan::call('optimize:cache');

        if ($this->argument('withDowntime')) {
            Artisan::call('up');
        }

        $this->info('Deploy completed successfully');

        return 0;
    }
}
