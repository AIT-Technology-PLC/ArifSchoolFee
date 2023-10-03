<?php

namespace App\DataTables;

use App\Services\InventoryHistory\ProductPerWarehouseHistoryService;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ProductPerWarehouseDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->editColumn('date', fn($item) => $item['date']->toDateString())
            ->editColumn('type', fn($item) => view('components.datatables.purple-solid-tag', ['content' => $item['type']]))
            ->editColumn('code', fn($item) => view('components.datatables.link', [
                'url' => $item['url'],
                'label' => $item['code'],
            ]))
            ->editColumn('quantity', fn($item) => view('components.datatables.item-quantity', compact('item')))
            ->editColumn('details', fn($item) => $item['details'])
            ->editColumn('balance', function ($item) {
                return view('components.datatables.green-solid-tag', [
                    'amount' => $item['balance'],
                    'unit' => $item['unit_of_measurement'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ProductPerWarehouseHistoryService $service)
    {
        return $service->get(request()->route('warehouse'), request()->route('product'));
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('date')->addClass('has-text-right'),
            Column::make('type'),
            Column::make('code')->addClass('has-text-centered')->title('Ref No'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('details'),
            Column::make('balance')->addClass('has-text-right'),
        ];
    }

    protected function filename(): string
    {
        return 'Product Movement History' . date('YmdHis');
    }
}
