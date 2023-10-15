<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\Gdn;
use App\Models\GdnDetail;
use App\Models\Product;
use App\Models\ProductBundle;
use App\Models\Reservation;
use App\Models\ReservationDetail;
use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Transaction;
use App\Scopes\BranchScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class BundleDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private $bundleProductIds;

    private $saleDetails;

    private $gdnDetails;

    private $reservationDetails;

    private $transactionDetails;

    private function get()
    {
        $this->bundleProductIds = ProductBundle::where('component_id', $this->product->id)->distinct('product_id')->pluck('product_id');

        $this->saleDetails = SaleDetail::query()
            ->where('warehouse_id', $this->warehouse->id)
            ->whereIn('product_id', $this->bundleProductIds)
            ->whereIn('sale_id', function ($query) {
                $query->select('id')
                    ->from('sales')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('subtracted_by')
                    ->whereNull('cancelled_by');
            })
            ->get()
            ->load([
                'sale' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );

        $this->gdnDetails = GdnDetail::query()
            ->where('warehouse_id', $this->warehouse->id)
            ->whereIn('product_id', $this->bundleProductIds)
            ->whereIn('gdn_id', function ($query) {
                $query->select('id')
                    ->from('gdns')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('subtracted_by')
                    ->whereNull('cancelled_by');
            })
            ->get()
            ->load([
                'gdn' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );

        $this->reservationDetails = ReservationDetail::query()
            ->where('warehouse_id', $this->warehouse->id)
            ->whereIn('product_id', $this->bundleProductIds)
            ->whereIn('reservation_id', function ($query) {
                $query->select('id')
                    ->from('reservations')
                    ->where('company_id', userCompany()->id)
                    ->whereNotNull('reserved_by')
                    ->whereNull('cancelled_by')
                    ->whereNotIn('id',
                        Reservation::withoutGlobalScopes([BranchScope::class])
                            ->whereHasMorph(
                                'reservable',
                                [Gdn::class, Sale::class],
                                function (Builder $query) {
                                    $query->withoutGlobalScopes([BranchScope::class])
                                        ->whereNotNull('subtracted_by');
                                })
                            ->pluck('id')
                    );
            })
            ->get()
            ->load([
                'reservation' => function ($query) {
                    return $query->withoutGlobalScopes([BranchScope::class])->with(['customer']);
                }]
            );

        $this->transactionDetails = collect();

        $transactions = Transaction::query()
            ->with([
                'transactionFields' => function ($query) {
                    return $query->where(function ($query) {
                        $query->where('key', 'subtracted_by')
                            ->orWhere('key', 'added_by');
                    });
                },
            ])
            ->whereHas('transactionFields', function ($query) {
                return $query->where(function ($query) {
                    $query->where('key', 'subtracted_by')
                        ->orWhere('key', 'added_by');
                });
            })
            ->get();

        if ($transactions->isNotEmpty()) {
            $transactions
                ->each(function ($transaction) {
                    $transaction
                        ->transactionDetails
                        ->whereIn('line', $transaction->transactionFields->pluck('line')->unique())
                        ->each(function ($transactionDetail) {
                            if ($this->bundleProductIds->contains($transactionDetail['product_id']) && $transactionDetail['warehouse_id'] == $this->warehouse->id) {
                                $this->transactionDetails->push($transactionDetail);
                            }
                        });
                });
        }
    }

    private function format()
    {
        $data = [];

        foreach ($this->saleDetails as $saleDetail) {
            foreach ($saleDetail->product->productBundles()->where('component_id', $this->product->id)->get() as $productBundle) {
                $data[] = [
                    'type' => 'INVOICE',
                    'url' => '/sales/' . $saleDetail->sale_id,
                    'code' => $saleDetail->sale->code,
                    'date' => $saleDetail->sale->issued_on,
                    'quantity' => number_format($saleDetail->quantity * $productBundle->quantity, 2, thousands_separator: ''),
                    'balance' => 0.00,
                    'unit_of_measurement' => $productBundle->component->unit_of_measurement,
                    'details' => Str::of($saleDetail->sale->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
                    'function' => 'subtract',
                ];
            }
        }

        foreach ($this->gdnDetails as $gdnDetail) {
            foreach ($gdnDetail->product->productBundles()->where('component_id', $this->product->id)->get() as $productBundle) {
                $data[] = [
                    'type' => 'DO',
                    'url' => '/gdns/' . $gdnDetail->gdn_id,
                    'code' => $gdnDetail->gdn->code,
                    'date' => $gdnDetail->gdn->issued_on,
                    'quantity' => number_format($gdnDetail->quantity * $productBundle->quantity, 2, thousands_separator: ''),
                    'balance' => 0.00,
                    'unit_of_measurement' => $productBundle->component->unit_of_measurement,
                    'details' => Str::of($gdnDetail->gdn->customer->company_name ?? 'Unknown')->prepend('Submitted to '),
                    'function' => 'subtract',
                ];
            }
        }

        foreach ($this->reservationDetails as $reservationDetail) {
            foreach ($reservationDetail->product->productBundles()->where('component_id', $this->product->id)->get() as $productBundle) {
                $data[] = [
                    'type' => 'RESERVED',
                    'url' => '/reservations/' . $reservationDetail->reservation_id,
                    'code' => $reservationDetail->reservation->code,
                    'date' => $reservationDetail->reservation->issued_on,
                    'quantity' => number_format($reservationDetail->quantity * $productBundle->quantity, 2, thousands_separator: ''),
                    'balance' => 0.00,
                    'unit_of_measurement' => $productBundle->component->unit_of_measurement,
                    'details' => Str::of($reservationDetail->reservation->customer->company_name ?? 'Unknown')->prepend('Reserved for '),
                    'function' => 'subtract',
                ];
            }
        }

        foreach ($this->transactionDetails as $transactionDetail) {
            foreach (Product::find($transactionDetail['product_id'])->productBundles()->where('component_id', $this->product->id)->get() as $productBundle) {
                $data[] = [
                    'type' => str()->upper($transactionDetail['transaction']->pad->abbreviation),
                    'url' => '/transactions/' . $transactionDetail['transaction']->id,
                    'code' => $transactionDetail['transaction']->code,
                    'date' => $transactionDetail['transaction']->issued_on,
                    'quantity' => number_format($transactionDetail['quantity'] * $productBundle->quantity, 2, thousands_separator: ''),
                    'balance' => 0.00,
                    'unit_of_measurement' => $productBundle->component->unit_of_measurement,
                    'details' => str($transactionDetail['transaction']->pad->inventory_operation_type)->append('ed', ' in ', $this->warehouse->name)->ucfirst(),
                    'function' => $transactionDetail['transaction']->pad->inventory_operation_type,
                ];
            }
        }

        $this->history = collect($data);
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        $this->get();

        $this->format();

        return $this->history;
    }
}
