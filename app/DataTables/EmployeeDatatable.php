<?php

namespace App\DataTables;

use App\Models\Employee;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class EmployeeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('name', fn($employee) => $employee->user->name)
            ->editColumn('branch', fn($employee) => $employee->user->warehouse->name)
            ->editColumn('email', fn($employee) => $employee->user->email)
            ->editColumn('position', fn($employee) => $employee->position)
            ->editColumn('role', fn($employee) => $employee->user->roles[0]->name)
            ->editColumn('status', fn($employee) => view('components.datatables.employee-status', compact('employee')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'enabled', fn($query) => $query->enabled())
                    ->when($keyword == 'disabled', fn($query) => $query->disabled());
            })
            ->editColumn('user.last_online_at', fn($employee) => $employee->user->last_online_at ? $employee->user->last_online_at->diffForHumans() : 'New User')
            ->editColumn('added on', fn($employee) => $employee->created_at->toFormattedDateString())
            ->editColumn('added by', fn($employee) => $employee->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($employee) => $employee->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($employee) {
                return view('components.datatables.employee-action', compact('employee'));
            })
            ->addIndexColumn();
    }

    public function query(Employee $employee)
    {
        return $employee
            ->newQuery()
            ->when(is_numeric(request('branch')), function ($query) {
                $query->whereHas('user', fn($q) => $q->where('users.warehouse_id', request('branch')));
            })
            ->when(request('status') == 'enabled', fn($query) => $query->enabled())
            ->when(request('status') == 'disabled', fn($query) => $query->disabled())
            ->select('employees.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'user.warehouse',
                'user.roles',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name', 'user.name')->addClass('text-green has-text-weight-bold'),
            Column::make('branch', 'user.warehouse.name'),
            Column::make('email', 'user.email')->visible(false),
            Column::make('position')->visible(false),
            Column::make('role', 'user.roles.name'),
            Column::make('status')->orderable(false),
            Column::make('user.last_online_at')->className('has-text-right')->title('Last Login'),
            Column::make('added on', 'created_at')->className('has-text-right')->visible(false),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Employees_' . date('YmdHis');
    }
}
