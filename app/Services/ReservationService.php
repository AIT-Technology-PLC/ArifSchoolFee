<?php

namespace App\Services;

use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationMade;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationService
{
    public function reserve($reservation)
    {
        if (!$reservation->isApproved()) {
            return [false, 'This reservation is not approved yet.'];
        }

        if ($reservation->isReserved()) {
            return [false, 'This reservation is already reserved.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($reservation->reservationDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($reservation) {
            InventoryOperationService::subtract($reservation->reservationDetails);

            InventoryOperationService::add($reservation->reservationDetails, 'reserved');

            $reservation->reserve();

            Notification::send(notifiables('Approve Reservation', $reservation->createdBy), new ReservationMade($reservation));
        });

        return [true, ''];
    }

    public function cancel($reservation)
    {
        if (!$reservation->isApproved()) {
            return [false, 'This reservation is not approved yet.'];
        }

        if ($reservation->isCancelled()) {
            return [false, 'This reservation is already cancelled.'];
        }

        if ($reservation->isConverted() && $reservation->reservable->isSubtracted()) {
            return [false, 'This reservation cannot be cancelled, it has been converted to DO.'];
        }

        if (!$reservation->isConverted() && !$reservation->isReserved()) {
            $reservation->cancel();

            return [true, ''];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($reservation->reservationDetails, 'reserved');

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($reservation) {
            if ($reservation->isConverted() && !$reservation->reservable->isSubtracted()) {
                $reservation->reservable()->forceDelete();
            }

            InventoryOperationService::subtract($reservation->reservationDetails, 'reserved');

            InventoryOperationService::add($reservation->reservationDetails);

            $reservation->cancel();

            Notification::send(notifiables('Approve Reservation', $reservation->createdBy), new ReservationCancelled($reservation));
        });

        return [true, ''];
    }
}
