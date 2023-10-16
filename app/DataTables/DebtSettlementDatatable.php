<?php

namespace App\DataTables;

use App\Models\DebtSettlement;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DebtSettlementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('method', fn($debtSettlement) => $debtSettlement->method)
            ->editColumn('bank_name', fn($debtSettlement) => $debtSettlement->bank_name ?? 'N/A')
            ->editColumn('reference_number', fn($debtSettlement) => $debtSettlement->reference_number ?? 'N/A')
            ->editColumn('settled_at', fn($debtSettlement) => $debtSettlement->settled_at->toFormattedDateString())
            ->editColumn('amount', fn($debtSettlement) => money($debtSettlement->amount))
            ->editColumn('description', fn($debtSettlement) => $debtSettlement->description ?? 'N/A')
            ->editColumn('actions', function ($debtSettlement) {
                return view('components.common.action-buttons', [
                    'model' => 'debt-settlements',
                    'id' => $debtSettlement->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(DebtSettlement $debtSettlement)
    {
        return $debtSettlement
            ->newQuery()
            ->select('debt_settlements.*')
            ->where('debt_id', request()->route('debt')->id);
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
        return 'DebtSettlement_' . date('YmdHis');
    }
}
