<?php

namespace App\DataTables;

use App\Models\AdvancementDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdvancementDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('employee name', fn($advancementDetail) => $advancementDetail->employee->user->name)
            ->editColumn('job_position', fn($advancementDetail) => $advancementDetail->job_position)
            ->editColumn('gross_salary', fn($advancementDetail) => $advancementDetail->gross_salary)
            ->editColumn('actions', function ($advancementDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'advancement-details',
                    'id' => $advancementDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(AdvancementDetail $advancementDetail)
    {
        return $advancementDetail
            ->newQuery()
            ->select('advancement_details.*')
            ->where('advancement_id', request()->route('advancement')->id)
            ->with([
                'employee.user',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee name', 'employee.user.name'),
            Column::make('job_position'),
            Column::make('gross_salary'),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'AdvancementDetail_' . date('YmdHis');
    }
}
