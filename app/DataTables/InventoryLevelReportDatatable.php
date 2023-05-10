<?php

namespace App\DataTables;

use App\Http\Requests\InventoryReportRequest;
use App\Reports\InventoryLevelReport;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Services\DataTable;

class InventoryLevelReportDatatable extends DataTable
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
                        'warehouseId' => $warehouse->id,
                        'unit' => $row['unit'],
                        'reorderQuantity' => $row['min_on_hand'],
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

    public function query(InventoryReportRequest $request)
    {
        $inventoryLevelReport = new InventoryLevelReport($request->validated());

        return $inventoryLevelReport->getInventoryLevels;
    }

    protected function getColumns()
    {
        $warehouses = $this->warehouses->pluck('name');

        return collect([
            '#' => [
                'sortable' => false,
            ],
            'product',
            isFeatureEnabled('Job Management') ? 'type' : null,
            'category',
            ...$warehouses,
            'total_balance',
        ])
            ->filter()
            ->toArray();
    }
    protected function filename()
    {
        return 'InventoryLevelReport_' . date('YmdHis');
    }
}
