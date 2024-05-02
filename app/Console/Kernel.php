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
        $schedule->command('cancel:expired-proforma-invoices')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('cancel:expired-reservations')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('notifications:delete-week')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('inventory:low-level-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('proforma-invoice:expiry-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('reservation:expiry-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('credit:due-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('debt:due-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('product:expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('supplier:licence-expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('customer:licence-expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('backup:run --disable-notifications')->withoutOverlapping()->evenInMaintenanceMode()->everySixHours(45)->unlessBetween('01:00', '12:00');

        $schedule->command('pos:remove')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        $schedule->command('pos:void')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        $schedule->command('pos:approve')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

        $schedule->command('company:deactivate')->withoutOverlapping()->evenInMaintenanceMode()->daily();

        $schedule->command('email:sales-report')->withoutOverlapping()->evenInMaintenanceMode()->dailyAt('06:00');

        $schedule->command('email:sales-report --monthly')->withoutOverlapping()->evenInMaintenanceMode()->monthlyOn(time: '06:30');

        $schedule->command('queue:work --stop-when-empty --max-time=3600 --tries=3')->runInBackground()->withoutOverlapping()->evenInMaintenanceMode()->dailyAt('07:00');
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
