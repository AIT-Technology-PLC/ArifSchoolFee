<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Gdn;
use App\Models\MerchandiseBatch;
use App\Models\ReservationDetail;
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

            $reservationDetails = $reservation->reservationDetails()->createMany($updatedReservationDetails);

            $deletableDetails = collect();

            foreach ($reservationDetails as $reservationDetail) {
                if ($reservationDetail->product->isBatchable() && is_null($reservationDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $reservationDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $reservationDetail->warehouse_id)
                        ->when($reservationDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$reservationDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($reservationDetail->id);

                        $reservation->reservationDetails()->create([
                            'product_id' => $reservationDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $reservationDetail->quantity ? $reservationDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $reservationDetail->original_unit_price,
                            'warehouse_id' => $reservationDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $reservationDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $reservationDetail->quantity - $merchandiseBatch->quantity;
                            $reservationDetail->quantity = $difference;
                        }
                    }
                }
            }

            ReservationDetail::whereIn('id', $deletableDetails)->forceDelete();

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
            return [false, 'This reservation is already converted to Delivery Order.'];
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
}