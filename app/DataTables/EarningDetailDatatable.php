<?php

namespace App\DataTables;

use App\Models\EarningDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EarningDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('employee name', fn($earningDetail) => $earningDetail->employee->user->name)
            ->editColumn('category', fn($earningDetail) => $earningDetail->earningCategory->name)
            ->editColumn('actions', function ($earningDetail) {
                return view('components.common.action-buttons', [
                    'model' => 'earning-details',
                    'id' => $earningDetail->id,
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(EarningDetail $earningDetail)
    {
        return $earningDetail
            ->newQuery()
            ->select('earning_details.*')
            ->where('earning_id', request()->route('earning')->id)
            ->with([
                'employee.user',
                'earningCategory:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('employee name', 'employee.user.name'),
            Column::make('category', 'earningCategory.name'),
            Column::make('amount'),
            Column::computed('actions'),
        ];
    }

    protected function filename()
    {
        return 'EarningDetail_' . date('YmdHis');
    }
}
