<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class ReservedInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        abort_if(!isFeatureEnabled('Reservation Management'), 403);

        $this->warehouses = auth()->user()->getAllowedWarehouses('read');
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
        $reservedMerchandises = DB::table('merchandises')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.company_id', '=', userCompany()->id)
            ->when(request('type') == 'finished goods', fn($query) => $query->where('products.type', '=', 'Finished Goods'))
            ->when(request('type') == 'raw material', fn($query) => $query->where('products.type', '=', 'Raw Material'))
            ->where('merchandises.reserved', '>', 0)
            ->whereIn('warehouses.id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
            ->select([
                'merchandises.reserved as reserved',
                'products.id as product_id',
                'products.name as product',
                'products.code as code',
                'products.type as type',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->get();

        $reservedMerchandises = $reservedMerchandises->groupBy('product_id')->map->keyBy('warehouse');

        $organizedReservedMerchandise = collect();

        foreach ($reservedMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseValue->first()->product,
                'code' => $merchandiseValue->first()->code ?? '',
                'product_id' => $merchandiseValue->first()->product_id,
                'unit' => $merchandiseValue->first()->unit,
                'type' => $merchandiseValue->first()->type,
                'category' => $merchandiseValue->first()->category,
                'total balance' => $merchandiseValue->sum('reserved'),
            ];

            foreach ($merchandiseValue as $key => $value) {

                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value->reserved);
            }

            $organizedReservedMerchandise->push($currentMerchandiseItem);
        }

        return $organizedReservedMerchandise;
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
