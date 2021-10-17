<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class AvailableInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = auth()->user()->getAllowedWarehouses('read');
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());

        $datatable->editColumn('product', function ($row) {
            return view('components.datatables.product-code', [
                'product' => $row['product'],
                'code' => $row['code'],
            ]);
        });

        $this->warehouses->each(function ($warehouse) use ($datatable) {

            $datatable->editColumn($warehouse->name, function ($row) use ($warehouse) {
                return view('components.datatables.history-link', [
                    'amount' => Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00,
                    'productId' => $row['product_id'],
                    'warehouseId' => $warehouse->id,
                    'unit' => $row['unit'],
                ]);
            });

            $datatable->editColumn('total balance', function ($row) {
                return view('components.datatables.green-solid-tag', [
                    'amount' => $row['total balance'] ?: 0.00,
                    'unit' => $row['unit'],
                ]);
            });

        });

        return $datatable
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'total balance',
                'product',
            ])
            ->addIndexColumn();
    }

    public function query()
    {
        $availableMerchandises = DB::table('merchandises')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.company_id', '=', userCompany()->id)
            ->where('merchandises.available', '>', 0)
            ->whereIn('warehouses.id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
            ->select([
                'merchandises.available as available',
                'products.id as product_id',
                'products.name as product',
                'products.code as code',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->get();

        $availableMerchandises = $availableMerchandises->groupBy('product_id')->map->keyBy('warehouse');

        $organizedAvailableMerchandise = collect();

        foreach ($availableMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseValue->first()->product,
                'code' => $merchandiseValue->first()->code ?? '',
                'product_id' => $merchandiseValue->first()->product_id,
                'unit' => $merchandiseValue->first()->unit,
                'category' => $merchandiseValue->first()->category,
                'total balance' => $merchandiseValue->sum('available'),
            ];

            foreach ($merchandiseValue as $key => $value) {

                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value->available);
            }

            $organizedAvailableMerchandise->push($currentMerchandiseItem);
        }

        return $organizedAvailableMerchandise;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return [
            '#' => [
                'sortable' => false,
            ],
            'product',
            'category',
            ...$warehouses,
            'total balance',
        ];

    }

    protected function filename()
    {
        return 'InventoryLevel_' . date('YmdHis');
    }
}
