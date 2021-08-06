<?php

namespace App\DataTables;

use App\Models\Warehouse;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Services\DataTable;

class InventoryLevelDatatable extends DataTable
{
    public function __construct()
    {
        $this->warehouses = (new Warehouse())->getAllWithoutRelations();
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());

        $this->warehouses->each(function ($warehouse) use ($datatable) {

            $datatable->editColumn($warehouse->name, function ($row) use ($warehouse) {
                $content = Arr::has($row, $warehouse->name) ? $row[$warehouse->name] : 0.00;

                return "<span class='tag has-text-centered is-small btn-green is-outlined'>" . number_format($content, 2, '.', '') . ' ' . $row['unit'] . '</span>';
            });

            $datatable->editColumn('total balance', function ($row) {
                $content = $row['total balance'] ?: 0.00;

                return "<span class='tag has-text-centered is-small bg-purple has-text-white'>" . number_format($content, 2) . ' ' . $row['unit'] . '</span>';
            });

        });

        return $datatable
            ->rawColumns([
                ...$this->warehouses->pluck('name')->toArray(),
                'total balance',
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
            ->where('merchandises.available', '>', 0)
            ->orWhere('merchandises.reserved', '>', 0)
            ->select([
                'products.name as product',
                'products.unit_of_measurement as unit',
                'product_categories.name as category',
                'warehouses.name as warehouse',
            ])
            ->selectRaw('merchandises.available + merchandises.reserved as on_hand')
            ->get();

        $onHandMerchandises = $onHandMerchandises->groupBy('product')->map->keyBy('warehouse');

        $organizedOnHandMerchandise = collect();

        foreach ($onHandMerchandises as $merchandiseKey => $merchandiseValue) {
            $currentMerchandiseItem = [
                'product' => $merchandiseKey,
                'unit' => $merchandiseValue->first()->unit,
                'category' => $merchandiseValue->first()->category,
            ];

            foreach ($merchandiseValue as $key => $value) {
                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, $key, $value->on_hand);
                $currentMerchandiseItem = Arr::add($currentMerchandiseItem, 'total balance', $merchandiseValue->sum('on_hand'));
            }

            $organizedOnHandMerchandise->push($currentMerchandiseItem);
        }

        return $organizedOnHandMerchandise;
    }

    public function html()
    {
        return $this->builder()
            ->responsive(true)
            ->scrollX(true)
            ->scrollY('500px')
            ->scrollCollapse(true)
            ->columns($this->getColumns())
            ->minifiedAjax()
            ->dom('lBfrtip')
            ->lengthMenu([
                [10, 25, 50, 75, 100, -1],
                [10, 25, 50, 75, 100, "All"],
            ])
            ->buttons([
                'colvis', 'excelHtml5', 'print', 'pdfHtml5',
            ])
            ->setTableId('inventoryleveldatatable-table')
            ->addTableClass('display is-hoverable is-size-7 nowrap')
            ->preDrawCallback("
                function(settings){
                    changeDtButton();
                    $('table').css('display', 'table')
                }
            ")
            ->orderBy(1, 'asc');
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
