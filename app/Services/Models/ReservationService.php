<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\AutoBatchStoringAction;
use App\Models\Gdn;
use App\Models\Sale;
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
                InventoryOperationService::subtract($reservation->reservationDetails, $reservation, 'reserved');

                InventoryOperationService::add($reservation->reservationDetails, $reservation);

                $reservation->reserved_by = null;
            }

            $reservation->update($updatedReservation);

            $reservation->reservationDetails()->forceDelete();

            $reservation->reservationDetails()->createMany($updatedReservationDetails);

            $reservation->createCustomFields($updatedReservation['customField'] ?? []);

            AutoBatchStoringAction::execute($reservation, $updatedReservationDetails, 'reservationDetails');

            $reservation->approved_by = null;

            $reservation->save();
        });

        return [true, ''];
    }

    public function reserve($reservation, $user)
    {
        if (!$user->hasWarehousePermission('sales',
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
            InventoryOperationService::subtract($reservation->reservationDetails, $reservation);

            InventoryOperationService::add($reservation->reservationDetails, $reservation, 'reserved');

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
            return [false, 'This reservation cannot be cancelled, it has been converted.'];
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

            InventoryOperationService::subtract($reservation->reservationDetails, $reservation, 'reserved');

            InventoryOperationService::add($reservation->reservationDetails, $reservation);

            $reservation->cancel();
        });

        return [true, ''];
    }

    public function convertToGdn($reservation, $user)
    {
        if (!$user->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permissions to convert to one or more of the warehouses.'];
        }

        if (!$reservation->isReserved()) {
            return [false, 'This reservation is not reserved yet.'];
        }

        if ($reservation->isConverted()) {
            return [false, 'This reservation is already converted.'];
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            $gdn = Gdn::create([
                'customer_id' => $reservation->customer_id ?? null,
                'contact_id' => $reservation->contact_id ?? null,
                'code' => nextReferenceNumber('gdns'),
                'discount' => $reservation->discount,
                'payment_type' => $reservation->payment_type,
                'cash_received_type' => $reservation->cash_received_type,
                'cash_received' => $reservation->cash_received,
                'description' => $reservation->description ?? '',
                'issued_on' => now(),
                'due_date' => $reservation->due_date,
            ]);

            $reservationDetails = $reservation->reservationDetails
                ->map(function ($detail) {
                    $detail = $detail->only('warehouse_id', 'product_id', 'merchandise_batch_id', 'quantity', 'original_unit_price', 'discount', 'description');
                    $detail['unit_price'] = $detail['original_unit_price'];
                    unset($detail['original_unit_price']);

                    return $detail;
                })
                ->toArray();

            $gdn->gdnDetails()->createMany($reservationDetails);

            $gdn->reservation()->save($reservation);

            $gdn->storeConvertedCustomFields($reservation, 'gdn');
        });

        return [true, ''];
    }

    public function approveAndReserve($reservation, $user)
    {
        if (!$user->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permissions to reserve from one or more of the warehouses.'];
        }

        if ($reservation->isApproved()) {
            return [false, 'This reservation is already approved.'];
        }

        if ($reservation->isReserved()) {
            return [false, 'This reservation is already reserved.'];
        }

        $unavailableProducts = InventoryOperationService::unavailableProducts($reservation->reservationDetails);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($reservation) {
            (new ApproveTransactionAction)->execute($reservation);

            InventoryOperationService::subtract($reservation->reservationDetails, $reservation);

            InventoryOperationService::add($reservation->reservationDetails, $reservation, 'reserved');

            $reservation->reserve();
        });

        return [true, ''];
    }

    public function convertToSale($reservation, $user)
    {
        if (!$reservation->company->canSaleSubtract()) {
            return [false, 'Invoice can not subtract from inventory. Contact your System Manager.'];
        }

        if (!$user->hasWarehousePermission('sales',
            $reservation->reservationDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permissions to convert to one or more of the warehouses.'];
        }

        if (!$reservation->isReserved()) {
            return [false, 'This reservation is not reserved yet.'];
        }

        if ($reservation->isConverted()) {
            return [false, 'This reservation is already converted.'];
        }

        DB::transaction(function () use ($reservation) {
            $reservation->convert();

            $sale = Sale::create([
                'customer_id' => $reservation->customer_id ?? null,
                'contact_id' => $reservation->contact_id ?? null,
                'code' => nextReferenceNumber('sales'),
                'payment_type' => $reservation->payment_type,
                'cash_received_type' => $reservation->cash_received_type,
                'cash_received' => $reservation->cash_received,
                'description' => $reservation->description ?? '',
                'issued_on' => now(),
                'due_date' => $reservation->due_date,
            ]);

            $reservationDetails = $reservation->reservationDetails
                ->map(function ($detail) {
                    $detail = $detail->only('warehouse_id', 'product_id', 'merchandise_batch_id', 'quantity', 'unit_price_after_discount', 'description');
                    $detail['unit_price'] = $detail['unit_price_after_discount'];
                    unset($detail['unit_price_after_discount']);

                    return $detail;
                })
                ->toArray();

            $sale->saleDetails()->createMany($reservationDetails);

            $sale->reservation()->save($reservation);

            $sale->storeConvertedCustomFields($reservation, 'sale');
        });

        return [true, ''];
    }
}
