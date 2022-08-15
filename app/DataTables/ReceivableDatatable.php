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
            ->eloquent($query)
            ->editColumn('company_name', fn($customer) => $customer->company_name)
            ->editColumn('current', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getCurrentCredit(),
                ]);
            })
            ->editColumn('1-30 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getCreditByPeriod(1, 30),
                ]);
            })
            ->editColumn('31-60 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getCreditByPeriod(31, 60),
                ]);
            })
            ->editColumn('61-90 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getCreditByPeriod(61, 90),
                ]);
            })
            ->editColumn('> 90 days', function ($customer) {
                return view('components.datatables.receivable-period', [
                    'amount' => $customer->getCreditByPeriod(91),
                ]);
            })
            ->editColumn('total balance', function ($customer) {
                return view('components.datatables.green-solid-tag', [
                    'amount' => $customer->getCreditByPeriod(),
                    'unit' => '',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Customer $customer)
    {
        return $customer
            ->newQuery()
            ->select('customers.*')
            ->with([
                'credits',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('company_name')->title('Customer'),
            Column::computed('current'),
            Column::computed('1-30 days'),
            Column::computed('31-60 days'),
            Column::computed('61-90 days'),
            Column::computed('> 90 days'),
            Column::computed('total balance'),
        ];
    }

    protected function filename()
    {
        return 'Receivable_' . date('YmdHis');
    }
}
