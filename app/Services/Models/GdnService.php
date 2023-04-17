<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Imports\GdnImport;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Notifications\GdnApproved;
use App\Notifications\GdnPrepared;
use App\Rules\CheckCustomerCreditLimit;
use App\Rules\MustBelongToCompany;
use App\Rules\UniqueReferenceNum;
use App\Rules\ValidatePrice;
use App\Rules\VerifyCashReceivedAmountIsValid;
use App\Services\Inventory\InventoryOperationService;
use App\Utilities\Notifiables;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

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
            'issued_on' => now(),
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
            'DO',
            $gdn->code,
            $gdn->customer->company_name ?? '',
            $gdn->approved_by,
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
        ini_set('max_execution_time', '-1');

        $gdn = DB::transaction(function () use ($importValidatedData) {
            $gdn = Gdn::create(Arr::except($importValidatedData, 'gdn'));

            $gdn->gdnDetails()->createMany($importValidatedData['gdn']);

            Notification::send(Notifiables::byNextActionPermission('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        });

        return [true, '', $gdn];
    }

    public function importValidatedData($import)
    {
        $products = Product::all(['id', 'name']);

        $sheets = (new GdnImport)->toArray($import);
        $data = $sheets[0][0];
        $data['gdn'] = $sheets[1];
        $data['code'] = nextReferenceNumber('gdns');
        $data['customer_id'] = Customer::firstWhere('company_name', $data['customer_name'])->id ?? null;

        foreach ($data['gdn'] as &$gdn) {
            $gdn['warehouse_id'] = Warehouse::firstWhere('name', $gdn['warehouse_name'])->id ?? null;
            $gdn['product_id'] = $products->firstWhere('name', str()->squish($gdn['product_name']))->id ?? null;
        }

        return Validator::make($data, [
            'code' => ['required', 'integer', new UniqueReferenceNum('gdns')],
            'gdn' => ['required', 'array'],
            'gdn.*.product_id' => ['required', 'integer', 'distinct', Rule::in(Product::activeForSale()->pluck('id'))],
            'gdn.*.warehouse_id' => ['required', 'integer', Rule::in(auth()->user()->getAllowedWarehouses('sales')->pluck('id'))],
            'gdn.*.unit_price' => ['nullable', 'numeric', new ValidatePrice(['gdn' => $data['gdn']])],
            'gdn.*.quantity' => ['required', 'numeric', 'gt:0'],
            'gdn.*.description' => ['nullable', 'string'],
            'gdn.*.discount' => ['nullable', 'numeric', 'min:0', 'max:100'],

            'customer_id' => ['nullable', 'integer', new MustBelongToCompany('customers'),
                new CheckCustomerCreditLimit(
                    $data['discount'],
                    $data['gdn'],
                    $data['payment_type'],
                    $data['cash_received_type'],
                    $data['cash_received']
                ),
            ],

            'sale_id' => ['nullable', 'integer', new MustBelongToCompany('sales')],
            'issued_on' => ['required', 'date'],
            'payment_type' => ['required', 'string', function ($attribute, $value, $fail) use ($data) {
                if ($value == 'Credit Payment' && is_null($data['customer_id'])) {
                    $fail('Credit Payment without customer is not allowed, please select a customer.');
                }
            },
            ],

            'cash_received_type' => ['required', 'string', function ($attribute, $value, $fail) use ($data) {
                if ($data['payment_type'] == 'Cash Payment' && $value != 'percent') {
                    $fail('When payment type is "Cash Payment", the type should be "Percent".');
                }
            },
            ],

            'description' => ['nullable', 'string'],

            'cash_received' => ['bail', 'required', 'numeric', 'gte:0',
                new VerifyCashReceivedAmountIsValid(
                    $data['payment_type'],
                    $data['discount'],
                    $data['gdn'],
                    $data['cash_received_type']
                ),
            ],

            'due_date' => ['nullable', 'date', 'after:issued_on', 'required_if:payment_type,Credit Payment', 'prohibited_if:payment_type,Cash Payment'],
            'discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ])->validated();
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
                Siv::where('purpose', 'DO')->where('ref_num', $gdn->code)->forceDelete();
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
