<?php

namespace App\DataTables;

use App\Models\Customer;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReceivableDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->editColumn('company_name', fn($customer) => $customer->company_name)
            ->editColumn('current', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getUndueCreditAmount(),
                ]);
            })
            ->editColumn('1-30 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getOverdueCreditAmountByPeriod(1, 30),
                ]);
            })
            ->editColumn('31-60 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getOverdueCreditAmountByPeriod(31, 60),
                ]);
            })
            ->editColumn('61-90 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getOverdueCreditAmountByPeriod(61, 90),
                ]);
            })
            ->editColumn('> 90 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getOverdueCreditAmountByPeriod(91),
                ]);
            })
            ->editColumn('total balance', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->credits->sum('credit_amount_unsettled'),
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Customer $customer)
    {
        return $customer
            ->newQuery()
            ->select('customers.*')
            ->whereHas('credits', fn($q) => $q->unsettled())
            ->with([
                'credits',
            ])
            ->get();
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('company_name')->title('Customer'),
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
        return 'Receivable_' . date('YmdHis');
    }
}
