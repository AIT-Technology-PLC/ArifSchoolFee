<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DeployRoutine extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deploy changes from onrica/smartwork repo main branch';

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
        Artisan::call('down');

        if (env('APP_ENV') == 'production') {
            $githubData = env('GITHUB_USERNAME') . ':' . env('GITHUB_PASSWORD');

            $this->info(exec('composer install --no-dev'));

            $this->newLine();

            $this->info(exec('git pull origin main https://' . $githubData . '@github.com/onrica/smartwork.git'));

            $this->newLine();
        }

        Artisan::call('migrate --force');

        Artisan::call('db:seed Plans --force');
        Artisan::call('db:seed Limits --force');
        Artisan::call('db:seed Features --force');
        Artisan::call('db:seed Integrations --force');
        Artisan::call('db:seed Permissions --force');

        Artisan::call('optimize:cache');

        Artisan::call('up');

        $this->info('Deploy completed successfully');

        return 0;
    }
}
