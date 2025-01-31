<?php

namespace App\DataTables\Admin;

use App\Models\Currency;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CurrencyDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->editColumn('name', fn($currency) => str()->ucfirst($currency->name))
            ->editColumn('exchange_rate', fn($currency) => $currency->exchange_rate !== null ? $currency->exchange_rate : 'N/A')
            ->editColumn('rate_source', fn($currency) => $currency->exchange_rate !== null ? ucfirst($currency->rate_source) : 'N/A')
            ->editColumn('exchange_rate_status', fn($currency) => view('components.datatables.currency-status', compact('currency')))
            ->editColumn('created_at', fn($currency) => $currency->created_at->toFormattedDateString())
            ->editColumn('actions', function ($currency) {
                return view('components.common.action-buttons', [
                    'model' => 'admin.currencies',
                    'id' => $currency->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Currency $currency)
    {
        return $currency
            ->newQuery()
            ->select('currencies.*');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('code'),
            Column::make('symbol'),
            Column::make('exchange_rate'),
            Column::make('exchange_rate_status')->orderable(false),
            Column::make('rate_source'),
            Column::make('created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Currencies_' . date('YmdHis');
    }
}

