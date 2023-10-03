<?php

namespace App\DataTables;

use App\Models\CreditSettlement;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CreditSettlementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('method', fn($creditSettlement) => $creditSettlement->method)
            ->editColumn('bank_name', fn($creditSettlement) => $creditSettlement->bank_name ?? 'N/A')
            ->editColumn('reference_number', fn($creditSettlement) => $creditSettlement->reference_number ?? 'N/A')
            ->editColumn('settled_at', fn($creditSettlement) => $creditSettlement->settled_at->toFormattedDateString())
            ->editColumn('amount', fn($creditSettlement) => money($creditSettlement->amount))
            ->editColumn('description', fn($creditSettlement) => $creditSettlement->description ?? 'N/A')
            ->editColumn('actions', function ($creditSettlement) {
                return view('components.common.action-buttons', [
                    'model' => 'credit-settlements',
                    'id' => $creditSettlement->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(CreditSettlement $creditSettlement)
    {
        return $creditSettlement
            ->newQuery()
            ->select('credit_settlements.*')
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

    protected function filename(): string
    {
        return 'Credit Settlements_' . date('YmdHis');
    }
}
