<?php

namespace App\DataTables;

use App\Models\DebitSettlement;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DebitSettlementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('method', fn($debitSettlement) => $debitSettlement->method)
            ->editColumn('bank_name', fn($debitSettlement) => $debitSettlement->bank_name ?? 'N/A')
            ->editColumn('reference_number', fn($debitSettlement) => $debitSettlement->reference_number ?? 'N/A')
            ->editColumn('settled_at', fn($debitSettlement) => $debitSettlement->settled_at->toFormattedDateString())
            ->editColumn('amount', fn($debitSettlement) => money($debitSettlement->amount))
            ->editColumn('description', fn($debitSettlement) => $debitSettlement->description ?? 'N/A')
            ->editColumn('actions', function ($debitSettlement) {
                return view('components.common.action-buttons', [
                    'model' => 'credit-settlements',
                    'id' => $debitSettlement->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(DebitSettlement $debitSettlement)
    {
        return $debitSettlement
            ->newQuery()
            ->select('debit_settlements.*')
            ->where('credit_id', request()->route('credit')->id);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('method'),
            Column::make('bank_name'),
            Column::make('reference_number'),
            Column::make('settled_at')->className('has-text-right')->title('Settled On'),
            Column::make('amount')->className('has-text-right'),
            Column::make('description'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'DebitSettlement_' . date('YmdHis');
    }
}
