<?php

namespace App\DataTables;

use App\Models\ReturnDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReturnDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('to', fn($returnDetail) => $returnDetail->warehouse->name)
            ->editColumn('product', function ($returnDetail) {
                return view('components.datatables.product-code', [
                    'product' => $returnDetail->product->name,
                    'code' => $returnDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($returnDetail) {
                return quantity($returnDetail->quantity, $returnDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($returnDetail) => $returnDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($returnDetail) => $returnDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('unit_price', fn($returnDetail) => money($returnDetail->unit_price))
            ->editColumn('total', fn($returnDetail) => money($returnDetail->totalPrice))
            ->editColumn('description', fn($returnDetail) => nl2br(e($returnDetail->description)))
            ->editColumn('actions', function ($returnDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'return-details',
                    'id' => $returnDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ReturnDetail $returnDetail)
    {
        return $returnDetail
            ->newQuery()
            ->select('return_details.*')
            ->where('return_id', request()->route('return')->id)
            ->with([
                'warehouse',
                'product',
                'merchandiseBatch',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('to', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('unit_price')->addClass('has-text-right'),
            Column::computed('total')->addClass('has-text-right'),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Return Details_' . date('YmdHis');
    }
}
