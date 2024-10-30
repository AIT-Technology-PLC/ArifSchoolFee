<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('notifications:delete-week')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('backup:run --disable-notifications')->withoutOverlapping()->evenInMaintenanceMode()->everySixHours(45)->unlessBetween('01:00', '12:00');

Schedule::command('company:deactivate')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('queue:work --stop-when-empty --max-time=3600 --tries=3')->runInBackground()->withoutOverlapping()->evenInMaintenanceMode()->dailyAt('07:00');
