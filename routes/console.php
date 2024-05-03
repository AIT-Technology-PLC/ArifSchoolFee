<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('cancel:expired-proforma-invoices')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('cancel:expired-reservations')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('notifications:delete-week')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('inventory:low-level-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('proforma-invoice:expiry-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('reservation:expiry-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('credit:due-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('debt:due-date-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('product:expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('supplier:licence-expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('customer:licence-expiry-date-close-notification')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('backup:run --disable-notifications')->withoutOverlapping()->evenInMaintenanceMode()->everySixHours(45)->unlessBetween('01:00', '12:00');

Schedule::command('pos:remove')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

Schedule::command('pos:void')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

Schedule::command('pos:approve')->withoutOverlapping()->evenInMaintenanceMode()->everyFiveMinutes();

Schedule::command('company:deactivate')->withoutOverlapping()->evenInMaintenanceMode()->daily();

Schedule::command('email:sales-report')->withoutOverlapping()->evenInMaintenanceMode()->dailyAt('06:00');

Schedule::command('email:sales-report --monthly')->withoutOverlapping()->evenInMaintenanceMode()->monthlyOn(time: '06:30');

Schedule::command('queue:work --stop-when-empty --max-time=3600 --tries=3')->runInBackground()->withoutOverlapping()->evenInMaintenanceMode()->dailyAt('07:00');
