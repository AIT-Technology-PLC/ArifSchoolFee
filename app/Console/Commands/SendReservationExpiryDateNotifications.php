<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ReservationExpirationIsClose;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Notification;

class SendReservationExpiryDateNotifications extends Command
{
    protected $signature = 'reservation:expiry-date-notification';

    protected $description = 'Send reservation expiration notifications to sales officers and system managers';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $companies = Company::enabled()->get(['id']);

        if ($companies->isEmpty()) {
            return 0;
        }

        foreach ($companies as $company) {
            $reservations = Reservation::query()
                ->where('company_id', $company->id)
                ->approved()
                ->notCancelled()
                ->whereRaw('DATEDIFF(expires_on, CURRENT_DATE) BETWEEN 1 AND 5')
                ->get(['id', 'warehouse_id']);

            if ($reservations->isEmpty()) {
                continue;
            }

            $users = User::query()
                ->permission('Read Reservation')
                ->where(function ($query) use ($reservations) {
                    $query->whereNull('warehouse_id')
                        ->orWhereIn('warehouse_id', $reservations->pluck('warehouse_id'));
                })
                ->whereHas('employee', function (Builder $query) use ($company) {
                    $query->where('company_id', $company->id);
                })
                ->get();

            if ($users->isEmpty()) {
                continue;
            }

            Notification::send($users, new ReservationExpirationIsClose($reservations));
        }

        return 0;
    }
}
