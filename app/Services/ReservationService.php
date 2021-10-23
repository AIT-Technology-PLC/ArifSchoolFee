<?php

namespace App\Services;

use App\Models\Gdn;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;

class ReservationService
{
    public function update($reservation, $updatedReservation, $updatedReservationDetails)
    {
        if ($reservation->isCancelled() || $reservation->isConverted()) {
            return [false, 'Cancelled or converted reservations can not be edited.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($reservation->reservationDetails, 'reserved');

        if ($reservation->isReserved() && $unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($updatedReservation, $updatedReservationDetails, $reservation) {
            if ($reservation->isReserved()) {
                InventoryOperationService::subtract($reservation->reservationDetails, 'reserved');

                InventoryOperationService::add($reservation->reservationDetails);

                $reservation->approved_by = null;

                $reservation->reserved_by = null;

                $reservation->save();
            }

            $reservation->update($updatedReservation);

            $reservation
                ->reservationDetails
                ->each(function ($reservationDetail, $key) use ($updatedReservationDetails) {
                    $reservationDetail->update($updatedReservationDetails[$key]);
                });
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
        });

        return [true, ''];
    }

    public function convertToGdn($reservation)
    {
        if (!$reservation->isReserved()) {
            return [false, 'This reservation is not reserved yet.'];
        }

        if ($reservation->isConverted()) {
            return [false, 'This reservation is already converted to Delivery Order.'];
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            $gdn = Gdn::create([
                'code' => NextReferenceNumService::table('gdns'),
                'customer_id' => $reservation->customer_id ?? null,
                'issued_on' => now(),
                'payment_type' => $reservation->payment_type,
                'description' => $reservation->description ?? '',
                'cash_received_in_percentage' => $reservation->cash_received_in_percentage,
            ]);

            $gdn->gdnDetails()->createMany($reservation->reservationDetails->toArray());

            $gdn->reservation()->save($reservation);
        });

        return [true, ''];
    }
}
