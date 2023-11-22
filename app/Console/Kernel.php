<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('routine:daily')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('backup:run --disable-notifications')->withoutOverlapping()->evenInMaintenanceMode()->everySixHours(45)->unlessBetween('01:00', '12:00');

        $schedule->command('pos:remove')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        $schedule->command('pos:void')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        $schedule->command('pos:approve')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        // $schedule->command('company:deactivate')->withoutOverlapping()->evenInMaintenanceMode()->daily();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
