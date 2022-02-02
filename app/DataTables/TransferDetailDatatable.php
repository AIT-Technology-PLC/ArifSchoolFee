<?php

namespace App\DataTables;

use App\Models\TransferDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransferDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('product', function ($transferDetail) {
                return view('components.datatables.product-code', [
                    'product' => $transferDetail->product->name,
                    'code' => $transferDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($transferDetail) {
                return quantity($transferDetail->quantity, $transferDetail->product->unit_of_measurement);
            })
            ->editColumn('description', fn($transferDetail) => nl2br(e($transferDetail->description)))
            ->editColumn('actions', function ($transferDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'transfer-details',
                    'id' => $transferDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(TransferDetail $transferDetail)
    {
        return $transferDetail
            ->newQuery()
            ->select('transfer_details.*')
            ->where('transfer_id', request()->route('transfer')->id)
            ->with('product');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('product', 'product.name'),
            Column::make('quantity'),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'Transfer Details_' . date('YmdHis');
    }
}
