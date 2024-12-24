<?php

namespace App\DataTables;

use App\Models\Staff;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class StaffDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('first_name', fn($staff) => str($staff->first_name)->append(' '.$staff->last_name))
            ->editColumn('branch', fn($staff) => $staff->warehouse->name)
            ->editColumn('department', fn($staff) => $staff->department->name)
            ->editColumn('designation', fn($staff) => $staff->designation->name)
            ->editColumn('added on', fn($staff) => $staff->created_at->toFormattedDateString())
            ->editColumn('added by', fn($staff) => $staff->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($staff) => $staff->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($staff) {
                return view('components.common.action-buttons', [
                    'model' => 'staff',
                    'id' => $staff->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Staff $staff)
    {
        return $staff
            ->newQuery()
            ->select('staff.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('staff.warehouse_id', request('branch')))
            ->when(is_numeric(request('department')), fn($query) => $query->where('staff.department_id', request('department')))
            ->when(is_numeric(request('designation')), fn($query) => $query->where('staff.designation_id', request('designation')))
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'warehouse:id,name',
                'department:id,name',
                'designation:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->title('Staff No'),
            Column::make('first_name')->title('Name'),
            Column::make('branch', 'warehouse.name'),
            Column::make('department', 'department.name'),
            Column::make('designation', 'designation.name'),
            Column::make('email'),
            Column::make('phone'),
            Column::make('added on', 'created_at')->className('has-text-right')->visible(false),
            Column::make('added by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Staffs_' . date('YmdHis');
    }
}
