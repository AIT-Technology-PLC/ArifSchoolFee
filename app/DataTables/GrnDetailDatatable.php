<?php

namespace App\DataTables;

use App\Models\GrnDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GrnDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('to', fn($grnDetail) => $grnDetail->warehouse->name)
            ->editColumn('product', function ($grnDetail) {
                return view('components.datatables.product-code', [
                    'product' => $grnDetail->product->name,
                    'code' => $grnDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($grnDetail) {
                return quantity($grnDetail->quantity, $grnDetail->product->unit_of_measurement);
            })
            ->editColumn('unit_cost', fn($grnDetail) => money($grnDetail->unit_cost))
            ->editColumn('batch_no', fn($grnDetail) => $grnDetail->batch_no)
            ->editColumn('expires_on', fn($grnDetail) => $grnDetail->expires_on?->toFormattedDateString())
            ->editColumn('description', fn($grnDetail) => nl2br(e($grnDetail->description)))
            ->editColumn('actions', function ($grnDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'grn-details',
                    'id' => $grnDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(GrnDetail $grnDetail)
    {
        return $grnDetail
            ->newQuery()
            ->select('grn_details.*')
            ->where('grn_id', request()->route('grn')->id)
            ->with([
                'warehouse',
                'product',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('to', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity'),
            Column::make('unit_cost'),
            Column::make('batch_no')->content('N/A')->visible(false),
            Column::make('expires_on')->content('N/A')->visible(false),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'GRN Details_' . date('YmdHis');
    }
}
