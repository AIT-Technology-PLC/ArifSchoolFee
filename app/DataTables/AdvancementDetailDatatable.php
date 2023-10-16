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
            ->editColumn('compensation', fn($advancementDetail) => $advancementDetail->compensation->name)
            ->editColumn('amount', fn($advancementDetail) => $advancementDetail->amount)
            ->editColumn('job_position', fn($advancementDetail) => $advancementDetail->job_position)
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
            Column::make('compensation'),
            Column::make('amount'),
            Column::make('job_position'),
            Column::computed('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'AdvancementDetail_' . date('YmdHis');
    }
}
