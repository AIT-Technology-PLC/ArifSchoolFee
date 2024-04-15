<?php

namespace App\Console\Commands;

use App\Models\Company;
use App\Models\Reservation;
use App\Models\User;
use App\Notifications\ExpiredReservationsCancelled;
use App\Services\Models\ReservationService;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class CancelExpiredReservations extends Command
{
    protected $signature = 'cancel:expired-reservations';

    protected $description = 'Cancel expired reservations';

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

        DB::transaction(function () use ($companies) {
            $cancelledReservations = collect();

            foreach ($companies as $company) {
                $reservations = Reservation::query()
                    ->where('company_id', $company->id)
                    ->approved()
                    ->expired()
                    ->notCancelled()
                    ->get();

                if ($reservations->count() == 0) {
                    continue;
                }

                $reservations->each(function ($reservation) use ($cancelledReservations) {
                    [$isExecuted, $message] = (new ReservationService)->cancel($reservation);

                    if ($isExecuted) {
                        return $cancelledReservations->push($reservation);
                    }
                });

                if ($cancelledReservations->count() == 0) {
                    continue;
                }

                $users = User::query()
                    ->permission('Read Reservation')
                    ->where(function ($query) use ($cancelledReservations) {
                        $query->whereNull('warehouse_id')
                            ->orWhereIn('warehouse_id', $cancelledReservations->pluck('warehouse_id'));
                    })
                    ->whereHas('employee', function (Builder $query) use ($company) {
                        $query->where('company_id', $company->id);
                    })
                    ->get();

                if ($users->isEmpty()) {
                    continue;
                }

                Notification::send($users, new ExpiredReservationsCancelled($cancelledReservations->count()));
            }
        });

        return 0;
    }
}
