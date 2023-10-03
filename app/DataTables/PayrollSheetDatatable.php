<?php

namespace App\DataTables;

use App\Actions\ProcessPayrollAction;
use App\Models\Compensation;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PayrollSheetDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $compensations;

    public function __construct()
    {
        $this->compensations = Compensation::active()->get();
    }

    public function dataTable($query)
    {
        return $this
            ->editCompensations(datatables()->collection($query))
            ->editColumn('employee_name', function ($row) {
                return view('components.datatables.link', [
                    'url' => route('payslips.print', [$row['payroll']->id, $row['employee_id']]),
                    'label' => $row['employee_name'],
                    'target' => '_blank',
                ]);
            })
            ->editColumn('working_days', fn($row) => $row['working_days'] . ' Days')
            ->editColumn('gross_salary', fn($row) => number_format($row['gross_salary'], 2))
            ->editColumn('taxable_income', fn($row) => number_format($row['taxable_income'], 2))
            ->editColumn('income_tax', fn($row) => number_format($row['income_tax'], 2))
            ->editColumn('deductions', fn($row) => number_format($row['deductions'], 2))
            ->editColumn('net_payable', fn($row) => number_format($row['net_payable'], 2))
            ->editColumn('absence_days', fn($row) => $row['absence_days'] . ' Days')
            ->editColumn('net_payable_after_absenteeism', fn($row) => number_format($row['net_payable_after_absenteeism'] ?? 0, 2))

            ->addIndexColumn();
    }

    private function editCompensations($datatable)
    {
        $this->compensations
            ->each(function ($compensation) use ($datatable) {
                $datatable->editColumn($compensation->name, fn($row) => number_format($row[$compensation->name] ?? 0, 2));
            });

        return $datatable;
    }

    public function query(ProcessPayrollAction $action)
    {
        return $action->execute(request()->route('payroll'));
    }

    protected function getColumns()
    {
        $columns = collect([
            Column::computed('#'),
            Column::make('employee_name')->addClass('has-text-weight-bold'),
            Column::make('working_days')->addClass('has-text-right'),
            Column::make('absence_days')->addClass('has-text-right'),
        ]);

        foreach ($this->compensations->whereIn('type', ['earning', 'none']) as $compensation) {
            $columns->push(
                Column::make($compensation->name)->addClass('has-text-right text-green')->visible(false)
            );
        }

        $columns->push(
            Column::make('gross_salary')->addClass('has-text-right has-text-weight-bold text-green'),
            Column::make('taxable_income')->addClass('has-text-right'),
            Column::make('income_tax')->addClass('has-text-right text-purple'),
        );

        foreach ($this->compensations->where('type', 'deduction') as $compensation) {
            $columns->push(
                Column::make($compensation->name)->addClass('has-text-right text-purple')->visible(false)
            );
        }

        $columns->push(
            Column::make('deductions')->addClass('has-text-right has-text-weight-bold text-purple'),
            Column::make('net_payable')->addClass('has-text-right has-text-weight-bold'),
            userCompany()->isBasicSalaryAfterAbsenceDeduction() ? null : Column::make('net_payable_after_absenteeism')->addClass('has-text-right has-text-weight-bold'),
        );

        return $columns->filter()->toArray();
    }

    protected function filename(): string
    {
        return 'Payroll Sheet_' . date('YmdHis');
    }
}
