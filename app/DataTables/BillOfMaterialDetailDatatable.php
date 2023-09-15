<?php

namespace App\DataTables;

use App\Models\BillOfMaterialDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BillOfMaterialDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', fn ($billOfMaterialDetail) => $billOfMaterialDetail->product->name)
            ->editColumn('code', fn ($billOfMaterialDetail) => $billOfMaterialDetail->product->code ?? 'N/A')
            ->editColumn('quantity', function ($billOfMaterialDetail) {
                return quantity($billOfMaterialDetail->quantity, $billOfMaterialDetail->product->unit_of_measurement);
            })
            ->editColumn('unit_cost', fn($billOfMaterialDetail) => number_format($billOfMaterialDetail->product->unit_cost, 2))
            ->editColumn('total_cost', fn($billOfMaterialDetail) => number_format($billOfMaterialDetail->product->unit_cost * $billOfMaterialDetail->quantity, 2))
            ->editColumn('actions', function ($billOfMaterialDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'bill-of-material-details',
                    'id' => $billOfMaterialDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(BillOfMaterialDetail $billOfMaterialDetail)
    {
        return $billOfMaterialDetail
            ->newQuery()
            ->select('bill_of_material_details.*')
            ->where('bill_of_material_id', request()->route('bill_of_material')->id)
            ->with([
                'product',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('code', 'product.code'),
            Column::make('quantity'),
            Column::computed('unit_cost'),
            Column::computed('total_cost'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'BillOfMaterialDetail_'.date('YmdHis');
    }
}
