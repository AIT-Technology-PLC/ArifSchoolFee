<?php

namespace App\DataTables;

use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class OnHandInventoryDatatable extends DataTable
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
                $content = Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00;

                return "<span class='tag is-small btn-green is-outlined'>" . number_format($content, 2, '.', '') . ' ' . $row['unit'] . '</span>';
            });

            $datatable->editColumn('total balance', function ($row) {
                $content = $row['total balance'] ?: 0.00;

                return "<span class='tag is-small bg-green has-text-white'>" . number_format($content, 2, '.', '') . ' ' . $row['unit'] . '</span>';
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
        $onHandMerchandises = DB::table('merchandises')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.company_id', '=', userCompany()->id)
            ->where(function ($query) {
                $query->where('merchandises.available', '>', 0)
                    ->orWhere('merchandises.reserved', '>', 0);
            })
            ->whereIn('warehouses.id', auth()->user()->getAllowedWarehouses('read')->pluck('id'))
            ->select([
                'products.id as id',
                'products.name as product',
                'products.code as code',
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
