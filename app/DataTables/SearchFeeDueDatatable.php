<?php

namespace App\DataTables;

use App\Models\AssignFeeMaster;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SearchFeeDueDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('admission no', fn($assignFeeMaster) => $assignFeeMaster->student->code)
            ->editColumn('due_date', fn($assignFeeMaster) => $assignFeeMaster->feeMaster->due_date?->toFormattedDateString())
            ->editColumn('amount', fn($assignFeeMaster) => money($assignFeeMaster->feeMaster->amount))
            ->editColumn('name', fn($assignFeeMaster) => str($assignFeeMaster->student->first_name)->append(' '.$assignFeeMaster->student->father_name))
            ->addIndexColumn();
    }

    public function query(AssignFeeMaster $assignFeeMaster)
    {
        $query = $assignFeeMaster
                ->newQuery()->select('assign_fee_masters.*')
                ->whereHas('feeMaster', function ($q) { $q->where('due_date', '<=', now()); })
                ->doesntHave('feePayments');

        $query->when(is_numeric(request('branch')), fn($q) => $q->whereHas('student', fn($q) => $q->where('warehouse_id', request('branch'))))
        ->when(is_numeric(request('class')), fn($q) => $q->whereHas('student', fn($q) => $q->where('school_class_id', request('class'))))
        ->when(is_numeric(request('section')), fn($q) => $q->whereHas('student', fn($q) => $q->where('section_id', request('section'))))
        ->when(is_numeric(request('group')), fn($q) => $q->whereHas('feeMaster.feeType', fn($q) => $q->where('fee_group_id', request('group'))));

        if (!request('branch') && !request('class') && !request('section') && !request('group')) {
            $query->whereRaw('1 = 0');
        }

        return $query->with([
            'student',
            'feeMaster',
        ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('admission no','student.code'),
            Column::make('name', 'student.first_name')->content('N/A'),
            Column::make('due_date', 'feeMaster.due_date')->content('N/A'),
            Column::make('amount', 'feeMaster.amount')->content('N/A'),
        ];
    }

    protected function filename(): string
    {
        return 'Search Fee Dues_' . date('YmdHis');
    }
}
