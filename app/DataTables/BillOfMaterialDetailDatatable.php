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
            ->editColumn('from', fn($billOfMaterialDetail) => $billOfMaterialDetail->product->name)
            ->editColumn('quantity', function ($billOfMaterialDetail) {
                return quantity($billOfMaterialDetail->quantity, $billOfMaterialDetail->product->name);
            })

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
            ->where('bill_of_material_id', request()->route('billOfMaterialDetail')->id)
            ->with([
                'product',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename()
    {
        return 'BillOfMaterialDetail_' . date('YmdHis');
    }
}