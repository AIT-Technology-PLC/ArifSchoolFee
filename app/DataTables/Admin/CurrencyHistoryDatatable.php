<?php

namespace App\DataTables\Admin;

use App\Models\CurrencyHistory;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CurrencyHistoryDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('exchange_rate', fn($currencyHistory) => money($currencyHistory->exchange_rate))
            ->editColumn('rate_source', fn($currencyHistory) => ucfirst($currencyHistory->rate_source))
            ->editColumn('created_at', fn($currencyHistory) => $currencyHistory->created_at->toFormattedDateString())
            ->addIndexColumn();
    }

    public function query(CurrencyHistory $currencyHistory)
    {
        return $currencyHistory
            ->newQuery()
            ->select('currency_histories.*')
            ->where('currency_id', request()->route('currency')->id)
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'currency:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('exchange_rate'),
            Column::make('rate_source'),
            Column::make('created_at'),
        ];
    }

    protected function filename(): string
    {
        return 'Currency Histories_' . date('YmdHis');
    }
}

