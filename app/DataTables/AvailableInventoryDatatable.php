<?php

namespace App\DataTables;

use App\Models\Warehouse;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class AvailableInventoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function __construct()
    {
        $this->warehouses = Warehouse::orderBy('name')
            ->whereIn('id', auth()->user()->readWarehouses())
            ->get(['id', 'name']);
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());

        $datatable->editColumn('product', function ($row) {
            if ($row['code']) {
                return $row['product'] . "<span class='has-text-grey has-has-text-weight-bold'> - " . $row['code'] . "</span>";
            }

            return $row['product'];
        });

        $this->warehouses->each(function ($warehouse) use ($datatable) {

            $datatable->editColumn($warehouse->name, function ($row) use ($warehouse) {
                $content = Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00;

                return "
                    <span class='is-hidden'>" . number_format($content, 2, '.', '') . "</span>" . "
                    <a href='/warehouses/" . $warehouse->id . "/products/" . $row['product_id'] . "'" . "data-title='View Product History'>
                        <span class='tag is-small btn-green is-outlined'>" . number_format($content, 2, '.', '') . ' ' . $row['unit'] .
                    '</span>' .
                    '</a>';

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
        $availableMerchandises = DB::table('merchandises')
            ->join('products', 'merchandises.product_id', '=', 'products.id')
            ->join('product_categories', 'products.product_category_id', '=', 'product_categories.id')
            ->join('warehouses', 'merchandises.warehouse_id', '=', 'warehouses.id')
            ->where('merchandises.company_id', '=', userCompany()->id)
            ->where('merchandises.available', '>', 0)
            ->whereIn('warehouses.id', auth()->user()->readWarehouses())
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
