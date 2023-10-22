<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Imports\GdnImport;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\MerchandiseBatch;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnApproved;
use App\Notifications\GdnPrepared;
use App\Services\Inventory\InventoryOperationService;
use App\Utilities\Notifiables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnService
{
    public function approve($gdn)
    {
        return DB::transaction(function () use ($gdn) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($gdn, GdnApproved::class, 'Subtract GDN');

            if ($gdn->payment_type == 'Deposits') {
                $gdn->customer->decrementBalance($gdn->grandTotalPriceAfterDiscount);
            }

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            $this->convertToCredit($gdn);

            return [true, $message];
        });
    }

    public function subtract($gdn, $user)
    {
        if (!$user->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if (!$gdn->isApproved()) {
            return [false, 'This Delivery Order is not approved yet.'];
        }

        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.'];
        }

        if ($gdn->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            InventoryOperationService::subtract($gdn->gdnDetails, $gdn, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }

    public function convertToCredit($gdn)
    {
        if (!$gdn->isApproved()) {
            return [false, 'Creating a credit for delivery order that is not approved is not allowed.'];
        }

        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.'];
        }

        if ($gdn->credit()->exists()) {
            return [false, 'A credit for this delivery order was already created.'];
        }

        if ($gdn->payment_type != 'Credit Payment' || $gdn->grand_total_price == 0) {
            return [false, 'Creating a credit for delivery order with 0.00 credit amount is not allowed.'];
        }

        if (!$gdn->customer()->exists()) {
            return [false, 'Creating a credit for delivery order that has no customer is not allowed.'];
        }

        if ($gdn->customer->hasReachedCreditLimit($gdn->payment_in_credit)) {
            return [false, 'The customer has exceeded the credit amount limit.'];
        }

        $gdn->credit()->create([
            'customer_id' => $gdn->customer_id,
            'code' => nextReferenceNumber('credits'),
            'cash_amount' => $gdn->payment_in_cash,
            'credit_amount' => $gdn->payment_in_credit,
            'credit_amount_settled' => 0.00,
            'issued_on' => $gdn->company->creditIssuedOnDate($gdn),
            'due_date' => $gdn->due_date,
        ]);

        return [true, ''];
    }

    public function convertToSiv($gdn, $user)
    {
        if (!$user->hasWarehousePermission('siv',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to convert to one or more of the warehouses.', ''];
        }

        if ($gdn->siv()->exists()) {
            return [false, 'Siv for this delivery order was already created.', ''];
        }

        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.', ''];
        }

        if (!$gdn->isSubtracted()) {
            return [false, 'This Delivery Order is not subtracted yet.', ''];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is closed.', ''];
        }

        $siv = (new ConvertToSivAction)->execute(
            $gdn,
            $gdn->customer->company_name ?? '',
            $gdn->gdnDetails()->get(['product_id', 'warehouse_id', 'quantity']),
        );

        return [true, '', $siv];
    }

    public function close($gdn)
    {
        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.'];
        }

        if (!$gdn->isSubtracted()) {
            return [false, 'This Delivery Order is not subtracted yet.'];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is already closed.'];
        }

        $gdn->close();

        return [true, ''];
    }

    public function import($importValidatedData)
    {
        $gdn = Gdn::create(Arr::except($importValidatedData, 'gdn'));

        $gdn->gdnDetails()->createMany($importValidatedData['gdn']);

        Notification::send(Notifiables::byNextActionPermission('Approve GDN'), new GdnPrepared($gdn));
    }

    public function formattedFromExcel($import)
    {
        $sheets = (new GdnImport)->toArray($import);

        $nextReferenceNumber = nextReferenceNumber('gdns');

        foreach ($sheets[0] as $key => $row) {
            $data[$key] = $row;

            $data[$key]['gdn'] = collect($sheets[1])->where('order_number', $row['order_number'])->toArray();

            $data[$key]['code'] = $nextReferenceNumber++;

            $data[$key]['customer_id'] = Customer::firstWhere('company_name', str()->squish($row['customer_name'] ?? ''))->id ?? null;

            foreach ($data[$key]['gdn'] as &$gdn) {
                $gdn['warehouse_id'] = Warehouse::firstWhere('name', str()->squish($gdn['warehouse_name']))->id ?? null;
                $gdn['merchandise_batch_id'] = MerchandiseBatch::firstWhere('batch_no', $gdn['batch_no'])->id ?? null;
                $gdn['product_id'] = Product::where('name', str()->squish($gdn['product_name']))->when(!empty($gdn['product_code']), fn($q) => $q->where('code', str()->squish($gdn['product_code'])))->first()->id ?? null;
            }
        }

        return $data;
    }

    public function convertToSale($gdn)
    {
        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.', ''];
        }

        if ($gdn->isConvertedToSale()) {
            return [false, 'This Delivery Order is already converted to invoice.', ''];
        }

        if (!$gdn->isSubtracted()) {
            return [false, 'This Delivery Order is not subtracted yet.', ''];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is closed.', ''];
        }

        $sale = DB::transaction(function () use ($gdn) {
            $sale = Sale::create([
                'customer_id' => $gdn->customer_id ?? null,
                'contact_id' => $gdn->contact_id ?? null,
                'code' => nextReferenceNumber('sales'),
                'payment_type' => $gdn->payment_type,
                'cash_received_type' => $gdn->cash_received_type,
                'cash_received' => $gdn->cash_received,
                'description' => $gdn->description ?? '',
                'issued_on' => now(),
                'due_date' => $gdn->due_date,
            ]);

            $sale->saleDetails()->createMany(
                $gdn
                    ->gdnDetails
                    ->map(function ($gdnDetail) {
                        return [
                            'product_id' => $gdnDetail->product_id,
                            'merchandise_batch_id' => $gdnDetail->merchandise_batch_id ?? null,
                            'quantity' => $gdnDetail->quantity,
                            'description' => $gdnDetail->description,
                            'unit_price' => $gdnDetail->unit_price_after_discount,
                        ];
                    })
                    ->toArray()
            );

            $gdn->convertToSale();

            return $sale;
        });

        return [true, '', $sale];
    }

    public function cancel($gdn)
    {
        if (!$gdn->isApproved()) {
            return [false, 'This Delivery Order is not approved yet.'];
        }

        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is already cancelled'];
        }

        if ($gdn->isClosed()) {
            return [false, 'This Delivery Order is already closed.'];
        }

        DB::transaction(function () use ($gdn) {
            $gdn->credit()->forceDelete();

            if ($gdn->payment_type == 'Deposits') {
                $gdn->customer->incrementBalance($gdn->grandTotalPriceAfterDiscount);
            }

            $gdn->cancel();

            if ($gdn->isSubtracted()) {
                InventoryOperationService::add($gdn->gdnDetails, $gdn);
                $gdn->add();
                $gdn->sale?->cancel();
                $gdn->siv?->forceDelete();
            }
        });

        return [true, ''];
    }

    public function approveAndSubtract($gdn, $user)
    {
        if (!$user->hasWarehousePermission('sales',
            $gdn->gdnDetails->pluck('warehouse_id')->toArray())) {
            return [false, 'You do not have permission to sell from one or more of the warehouses.'];
        }

        if ($gdn->isApproved()) {
            return [false, 'This Delivery Order is already approved.'];
        }

        if ($gdn->isCancelled()) {
            return [false, 'This Delivery Order is cancelled.'];
        }

        if ($gdn->isSubtracted()) {
            return [false, 'This Delivery Order is already subtracted from inventory'];
        }

        $from = $gdn->reservation()->exists() ? 'reserved' : 'available';

        $unavailableProducts = InventoryOperationService::unavailableProducts($gdn->gdnDetails, $from);

        if ($unavailableProducts->isNotEmpty()) {
            return [false, $unavailableProducts];
        }

        DB::transaction(function () use ($gdn, $from) {
            (new ApproveTransactionAction)->execute($gdn);

            if ($gdn->payment_type == 'Deposits') {
                $gdn->customer->decrementBalance($gdn->grandTotalPriceAfterDiscount);
            }

            $this->convertToCredit($gdn);

            InventoryOperationService::subtract($gdn->gdnDetails, $gdn, $from);

            $gdn->subtract();
        });

        return [true, ''];
    }
}
