<?php

namespace App\DataTables;

use App\Models\Supplier;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayableDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->editColumn('company_name', fn($supplier) => $supplier->company_name)
            ->editColumn('current', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->getUndueDebtAmount(),
                ]);
            })
            ->editColumn('1-30 days', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->getOverdueDebtAmountByPeriod(1, 30),
                ]);
            })
            ->editColumn('31-60 days', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->getOverdueDebtAmountByPeriod(31, 60),
                ]);
            })
            ->editColumn('61-90 days', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->getOverdueDebtAmountByPeriod(61, 90),
                ]);
            })
            ->editColumn('> 90 days', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->getOverdueDebtAmountByPeriod(91),
                ]);
            })
            ->editColumn('total balance', function ($supplier) {
                return view('components.datatables.receivable-period', [
                    'amount' => $supplier->debts->sum('debt_amount_unsettled'),
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Supplier $supplier)
    {
        return $supplier
            ->newQuery()
            ->select('suppliers.*')
            ->whereHas('debts', fn($q) => $q->unsettled())
            ->with([
                'debts',
            ])
            ->get();
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('company_name')->title('Supplier'),
            Column::make('current'),
            Column::make('1-30 days'),
            Column::make('31-60 days'),
            Column::make('61-90 days'),
            Column::make('> 90 days'),
            Column::make('total balance'),
        ];
    }

    protected function filename(): string
    {
        return 'Payable_' . date('YmdHis');
    }
}
