<?php

namespace App\DataTables;

use App\Models\Merchandise;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Services\DataTable;

class OnHandInventoryDatatable extends DataTable
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
            ->editColumn('product', function ($row) {
                return view('components.datatables.product-code', [
                    'product' => $row['product'],
                    'code' => $row['code'],
                ]);
            })
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'total balance',
                'product',
            ])
            ->addIndexColumn();
    }

    private function editWarehouses($datatable)
    {
        $this->warehouses->each(function ($warehouse) use ($datatable) {
            $datatable
                ->editColumn($warehouse->name, function ($row) use ($warehouse) {
                    return view('components.datatables.green-outlined-tag', [
                        'amount' => Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00,
                        'unit' => $row['unit'],
                    ]);
                })
                ->editColumn('total balance', function ($row) {
                    return view('components.datatables.green-solid-tag', [
                        'amount' => $row['total balance'] ?: 0.00,
                        'unit' => $row['unit'],
                    ]);
                });
        });

        return $datatable;
    }

    public function query()
    {
        $onHandMerchandises = Merchandise::query()
            ->where('products.type', '!=', 'Services')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->when(request('type') == 'finished goods', fn($query) => $query->where('products.type', '=', 'Finished Goods'))
            ->when(request('type') == 'raw material', fn($query) => $query->where('products.type', '=', 'Raw Material'))
            ->where(function ($query) {
                $query->where('merchandises.available', '>', 0)
                    ->orWhere('merchandises.reserved', '>', 0);
            })
            ->whereIn('warehouses.id', authUser()->getAllowedWarehouses('read')->pluck('id'))
            ->select([
                'products.id as id',
                'products.name as product',
                'products.code as code',
                'products.type as type',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->selectRaw('merchandises.available + merchandises.reserved as on_hand')
            ->get();

        $onHandMerchandises = $onHandMerchandises->groupBy('id')->map->keyBy('warehouse');

        $organizedOnHandMerchandise = collect();

        foreach ($onHandMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseValue->first()->product,
                'code' => $merchandiseValue->first()->code ?? '',
                'unit' => $merchandiseValue->first()->unit,
                'type' => $merchandiseValue->first()->type,
                'category' => $merchandiseValue->first()->category,
                'total balance' => $merchandiseValue->sum('on_hand'),
            ];

            foreach ($merchandiseValue as $key => $value) {
                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value->on_hand);
            }

            $organizedOnHandMerchandise->push($currentMerchandiseItem);
        }

        return $organizedOnHandMerchandise;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return collect([
            '#' => [
                'sortable' => false,
            ],
            'product',
            userCompany()->plan->isPremium() ? 'type' : null,
            'category',
            ...$warehouses,
            'total balance',
        ])->filter()->toArray();
    }

    protected function filename()
    {
        return 'InventoryLevel_' . date('YmdHis');
    }
}