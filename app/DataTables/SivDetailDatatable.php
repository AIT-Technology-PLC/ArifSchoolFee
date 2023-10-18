<?php

namespace App\DataTables;

use App\Models\SivDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SivDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('from', fn($sivDetail) => $sivDetail->warehouse->name)
            ->editColumn('product', function ($sivDetail) {
                return view('components.datatables.product-code', [
                    'product' => $sivDetail->product->name,
                    'code' => $sivDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($sivDetail) {
                return quantity($sivDetail->quantity, $sivDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($sivDetail) => $sivDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($sivDetail) => $sivDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('description', fn($sivDetail) => nl2br(e($sivDetail->description)))
            ->editColumn('actions', function ($sivDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'siv-details',
                    'id' => $sivDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(SivDetail $sivDetail)
    {
        return $sivDetail
            ->newQuery()
            ->select('siv_details.*')
            ->where('siv_id', request()->route('siv')->id)
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
            Column::make('from', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity'),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('description')->visible(false),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'SIV Details_' . date('YmdHis');
    }
}
