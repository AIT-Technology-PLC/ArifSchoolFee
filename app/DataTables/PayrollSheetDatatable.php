<?php

namespace App\DataTables;

use App\Actions\ProcessPayrollAction;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollSheetDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->collection($query)
            ->editColumn('name', fn($row) => $row['employee_name'])
            ->editColumn('gross_salary', fn($row) => money($row['gross_salary']))
            ->editColumn('taxable_income', fn($row) => money($row['taxable_income']))
            ->editColumn('income_tax', fn($row) => money($row['income_tax']))
            ->editColumn('deductions', fn($row) => money($row['deductions']))
            ->editColumn('net_payable', fn($row) => money($row['net_payable']))
            ->editColumn('absence_days', fn($row) => $row['absence_days'] . ' Days')
            ->editColumn('absence_deduction', fn($row) => money($row['absence_deduction']))
            ->editColumn('net_payable_after_absenteeism', fn($row) => money($row['net_payable_after_absenteeism']))

            ->addIndexColumn();
    }

    public function query(ProcessPayrollAction $action)
    {
        return $action->execute(request()->route('payroll'));
    }

    protected function getColumns()
    {
        return
            [
            Column::computed('#'),
            Column::make('employee_name'),
            Column::make('gross_salary')->addClass('has-text-right text-green'),
            Column::make('taxable_income')->addClass('has-text-right'),
            Column::make('income_tax')->addClass('has-text-right'),
            Column::make('deductions')->addClass('has-text-right text-gold'),
            Column::make('net_payable')->addClass('has-text-right text-green'),
            Column::make('absence_days')->addClass('has-text-right text-gold'),
            Column::make('absence_deduction')->addClass('has-text-right text-gold'),
            Column::make('net_payable_after_absenteeism')->addClass('has-text-right text-green'),
        ];
    }

    protected function filename()
    {
        return 'Payroll Sheet_' . date('YmdHis');
    }
}
