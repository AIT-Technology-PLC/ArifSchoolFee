<?php

namespace App\Services;

use App\Models\Gdn;
use App\Notifications\GdnPrepared;
use App\Notifications\ReservationCancelled;
use App\Notifications\ReservationConverted;
use App\Notifications\ReservationMade;
use App\Notifications\ReservationPrepared;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReservationService
{
    public function update($request, $reservation)
    {
        if ($reservation->isCancelled() || $reservation->isConverted()) {
            return [false, 'Cancelled or converted reservations can not be edited.'];
        }

        DB::transaction(function () use ($request, $reservation) {
            if ($reservation->isReserved()) {
                InventoryOperationService::subtract($reservation->reservationDetails, 'reserved');

                InventoryOperationService::add($reservation->reservationDetails);

                $reservation->approved_by = null;

                $reservation->reserved_by = null;

                $reservation->save();
            }

            $reservation->update($request->except('reservation'));

            $reservation
                ->reservationDetails
                ->each(function ($reservationDetail, $key) use ($request) {
                    $reservationDetail->update($request->reservation[$key]);
                });

            Notification::send(notifiables('Approve Reservation'), new ReservationPrepared($reservation));
        });

        return [true, ''];
    }

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

    public function convertToGdn($reservation)
    {
        if (!$reservation->isReserved()) {
            return [false, 'This reservation is not reserved yet.'];
        }

        if ($reservation->isConverted()) {
            return [false, 'This reservation is already to Delivery Order.'];
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            Notification::send(
                notifiables('Approve Reservation', $reservation->createdBy),
                new ReservationConverted($reservation)
            );

            $gdn = Gdn::create([
                'code' => Gdn::byBranch()->max('code') + 1,
                'customer_id' => $reservation->customer_id ?? null,
                'issued_on' => today(),
                'payment_type' => $reservation->payment_type,
                'description' => $reservation->description ?? '',
                'cash_received_in_percentage' => $reservation->cash_received_in_percentage,
            ]);

            $gdn->gdnDetails()->createMany($reservation->reservationDetails->toArray());

            $gdn->reservation()->save($reservation);

            Notification::send(notifiables('Approve GDN', $gdn->createdBy), new GdnPrepared($gdn));
        });

        return [true, ''];
    }
}
