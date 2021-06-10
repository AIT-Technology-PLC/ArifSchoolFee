<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;

class Install extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run composer, copy env file, generate key, run migrations, run seeders';

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
        if (App::environment('production')) {
            $this->error('THIS COMMAND IS DESTRUCTIVE AND CAN NOT AND MUST NOT BE RUN ON PRODUCTION ENVIRONMENT');
        }

        if (App::environment('local', 'staging')) {

            $confirmationQuestion = "First you should create an empty, new database with any name and assign that name to the DB_DATABASE property
            in the .env file and you must also assign the DB_USERNAME and DB_PASSWORD fields then write 'yes' to continue! ";

            if (!file_exists(__DIR__ . '/../../../.env')) {
                shell_exec('cp .env.example .env');
            }

            $this->call('key:generate');

            if ($this->confirm($confirmationQuestion)) {
                $this->call('migrate:fresh');
                $this->call('db:seed');
            }

        }

        return 0;
    }
}
