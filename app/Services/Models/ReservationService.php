<?php

namespace App\Services\Models;

use App\Models\Gdn;
use App\Services\Inventory\InventoryOperationService;
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

                $reservation->reserved_by = null;
            }

            $reservation->update($updatedReservation);

            $reservation
                ->reservationDetails
                ->each(function ($reservationDetail, $key) use ($updatedReservationDetails) {
                    $reservationDetail->update($updatedReservationDetails[$key]);
                });

            $reservation->approved_by = null;

            $reservation->save();
        });

        return [true, ''];
    }

    public function reserve($reservation)
    {
        if (!auth()->user()->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permissions to reserve from one or more of the warehouses.'];
        }

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
                $reservation->reservable()->dissociate();
            }

            InventoryOperationService::subtract($reservation->reservationDetails, 'reserved');

            InventoryOperationService::add($reservation->reservationDetails);

            $reservation->cancel();
        });

        return [true, ''];
    }

    public function convertToGdn($reservation)
    {
        if (!auth()->user()->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permissions to convert to one or more of the warehouses.'];
        }

        if (!$reservation->isReserved()) {
            return [false, 'This reservation is not reserved yet.'];
        }

        if ($reservation->isConverted()) {
            return [false, 'This reservation is already converted to Delivery Order.'];
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            $gdn = Gdn::create([
                'customer_id' => $reservation->customer_id ?? null,
                'code' => nextReferenceNumber('gdns'),
                'discount' => $reservation->discount * 100,
                'payment_type' => $reservation->payment_type,
                'cash_received_in_percentage' => $reservation->cash_received_in_percentage,
                'description' => $reservation->description ?? '',
                'issued_on' => now(),
                'due_date' => $reservation->due_date,
            ]);

            $reservationDetails = $reservation->reservationDetails
                ->map(function ($detail) {
                    $detail = $detail->only('warehouse_id', 'product_id', 'quantity', 'original_unit_price', 'discount', 'description');
                    $detail['unit_price'] = $detail['original_unit_price'];
                    $detail['discount'] = $detail['discount'] * 100;
                    unset($detail['original_unit_price']);

                    return $detail;
                })
                ->toArray();

            $gdn->gdnDetails()->createMany($reservationDetails);

            $gdn->reservation()->save($reservation);
        });

        return [true, ''];
    }
}
