<?php

namespace App\DataTables;

use App\Models\ExchangeDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExchangeDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('from', fn($exchangeDetail) => $exchangeDetail->warehouse->name)
            ->editColumn('product', function ($exchangeDetail) {
                return view('components.datatables.product-code', [
                    'product' => $exchangeDetail->product->name,
                    'code' => $exchangeDetail->product->code ?? '',
                ]);
            })
            ->editColumn('quantity', function ($exchangeDetail) {
                return quantity($exchangeDetail->quantity, $exchangeDetail->product->unit_of_measurement);
            })

            ->editColumn('returned_quantity', function ($exchangeDetail) {
                return quantity($exchangeDetail->returned_quantity, $exchangeDetail->product->unit_of_measurement);
            })
            ->editColumn('batch_no', fn($exchangeDetail) => $exchangeDetail->merchandiseBatch?->batch_no)
            ->editColumn('expires_on', fn($exchangeDetail) => $exchangeDetail->merchandiseBatch?->expires_on?->toFormattedDateString())
            ->editColumn('unit_price', fn($exchangeDetail) => money($exchangeDetail->unit_price))
            ->editColumn('total', fn($exchangeDetail) => money($exchangeDetail->totalPrice))
            ->editColumn('actions', function ($exchangeDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'exchange-details',
                    'id' => $exchangeDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(ExchangeDetail $exchangeDetail)
    {
        return $exchangeDetail
            ->newQuery()
            ->select('exchange_details.*')
            ->where('exchange_id', request()->route('exchange')->id)
            ->with([
                'warehouse',
                'product',
                'merchandiseBatch',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('from', 'warehouse.name'),
            Column::make('product', 'product.name'),
            Column::make('quantity')->addClass('has-text-right'),
            Column::make('returned_quantity')->addClass('has-text-right')->visible(false),
            Column::make('batch_no', 'merchandiseBatch.batch_no')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('expires_on', 'merchandiseBatch.expires_on')->title('Expiry Date')->content('N/A')->addClass('has-text-right')->visible(false),
            Column::make('unit_price')->addClass('has-text-right'),
            Column::computed('total')->addClass('has-text-right'),
            Column::computed('actions'),
        ];

        return collect($columns)->filter()->toArray();
    }

    protected function filename(): string
    {
        return 'ExchangeDetail_' . date('YmdHis');
    }
}
