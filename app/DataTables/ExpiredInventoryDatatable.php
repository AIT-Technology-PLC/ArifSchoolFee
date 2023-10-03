<?php

namespace App\DataTables;

use App\Models\MerchandiseBatch;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Services\DataTable;

class ExpiredInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = authUser()->getAllowedWarehouses('read');
    }

    public function dataTable($query)
    {
        return $this
            ->editWarehouses(datatables()->collection($query->all()))
            ->editColumn('product', fn($row) => $row['product'])
            ->editColumn('code', fn($row) => $row['code'] ?? 'N/A')
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'total_balance',
                'product',
            ])
            ->addIndexColumn();
    }

    private function editWarehouses($datatable)
    {
        $this->warehouses->each(function ($warehouse) use ($datatable) {
            $datatable
                ->editColumn($warehouse->name, function ($row) use ($warehouse) {
                    return view('components.datatables.history-link', [
                        'amount' => Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00,
                        'productId' => $row['product_id'],
                        'warehouseId' => $warehouse->id,
                        'unit' => $row['unit'],
                        'expired' => 'expired',
                        'reorderQuantity' => 0,
                    ]);
                })
                ->editColumn('total_balance', function ($row) {
                    return view('components.datatables.green-solid-tag', [
                        'amount' => $row['total_balance'] ?: 0.00,
                        'unit' => $row['unit'],
                    ]);
                });
        });

        return $datatable;
    }

    public function query()
    {
        $expiredMerchandises = MerchandiseBatch::query()
            ->available()
            ->when(request('expiryType') == 'now' || empty(request('expiryType')), fn($query) => $query->expired())
            ->when(request('expiryType') == 'near', fn($query) => $query->nearToBeExpired())
            ->join('merchandises', 'merchandise_batches.merchandise_id', '=', 'merchandises.id')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->when(request('type') == 'finished goods', fn($query) => $query->where('products.type', '=', 'Finished Goods'))
            ->when(request('type') == 'raw material', fn($query) => $query->where('products.type', '=', 'Raw Material'))
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->where('products.type', '!=', 'Services')
            ->groupBy(['merchandise_id', 'product_id', 'warehouse_id', 'product', 'code', 'type', 'unit', 'category', 'warehouse'])
            ->select([
                'merchandises.product_id as product_id',
                'products.name as product',
                'products.code as code',
                'products.type as type',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->selectRaw('SUM(merchandise_batches.quantity) AS quantity')
            ->get();

        $expiredMerchandises = $expiredMerchandises->groupBy('product_id')->map->keyBy('warehouse');

        $organizedExpiredMerchandise = collect();

        foreach ($expiredMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentExpiredMerchandiseItem = [
                'product' => $merchandiseValue->first()->product,
                'product_id' => $merchandiseValue->first()->product_id,
                'code' => $merchandiseValue->first()->code ?? '',
                'unit' => $merchandiseValue->first()->unit,
                'type' => $merchandiseValue->first()->type,
                'category' => $merchandiseValue->first()->category,
                'total_balance' => $merchandiseValue->sum('quantity'),
            ];

            foreach ($merchandiseValue as $key => $value) {
                $currentExpiredMerchandiseItem = Arr::add($currentExpiredMerchandiseItem, $key, $value->quantity);
            }

            $organizedExpiredMerchandise->push($currentExpiredMerchandiseItem);
        }

        return $organizedExpiredMerchandise;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return collect([
            '#' => [
                'sortable' => false,
            ],
            'product',
            'code',
            'type',
            'category',
            ...$warehouses,
            'total_balance',
        ])
            ->filter()
            ->toArray();
    }

    protected function filename(): string
    {
        return 'ExpiredInventory_' . date('YmdHis');
    }
}
