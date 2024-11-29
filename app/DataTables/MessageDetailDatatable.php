<?php

namespace App\DataTables;

use App\Models\MessageDetail;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;
use Illuminate\Support\Arr;

class MessageDetailDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('User Name', fn($messageDetail) => $messageDetail->employee?->user->name)
            ->editColumn('User Email', fn($messageDetail) => $messageDetail->employee?->user->email)
            ->editColumn('User Phone', fn($messageDetail) => $messageDetail->employee?->phone)
            ->editColumn('Student Name', fn($messageDetail) => $messageDetail->student?->first_name)
            ->editColumn('Student Last Name', fn($messageDetail) => $messageDetail->student?->last_name)
            ->editColumn('Student Email', fn($messageDetail) => $messageDetail->student?->email)
            ->editColumn('Student Phone', fn($messageDetail) => $messageDetail->student?->phone)
            ->editColumn('Staff Name', fn($messageDetail) => $messageDetail->staff?->first_name)
            ->editColumn('Staff Last Name', fn($messageDetail) => $messageDetail->staff?->last_name)
            ->editColumn('Staff Email', fn($messageDetail) => $messageDetail->staff?->email)
            ->editColumn('Staff Phone', fn($messageDetail) => $messageDetail->staff?->phone)
            ->addIndexColumn();
    }

    public function query(MessageDetail $messageDetail)
    {
        return $messageDetail
            ->newQuery()
            ->select('message_details.*')
            ->where('message_id', request()->route('message')->id)
            ->with([
                'employee',
                'student',
                'staff',
            ]);
    }

    protected function getColumns()
    {
        return Arr::whereNotNull([
            Column::computed('#')->addClass('text-center'),
            request()->route('message')->hasReceivedBy() ? Column::make('received_by') : null,
            request()->route('message')->hasEmployee() ? Column::make('User Name', 'employee.name')  : null,
            request()->route('message')->hasStudent() ? Column::make('Student Name', 'student.first_name')  : null,
            request()->route('message')->hasStudent() ? Column::make('Student Last Name', 'student.last_name')  : null,
            request()->route('message')->hasStaff() ? Column::make('Staff Name', 'staff.first_name')  : null,
            request()->route('message')->hasStaff() ? Column::make('Staff Last Name', 'staff.last_name')  : null,
            request()->route('message')->hasEmployee() ? Column::make('User Email', 'employee.email')  : null,
            request()->route('message')->hasStudent() ? Column::make('Student Email', 'student.email')  : null,
            request()->route('message')->hasStaff() ? Column::make('Staff Email', 'staff.email')  : null,
            request()->route('message')->hasEmployee() ? Column::make('User Phone', 'employee.phone')  : null,
            request()->route('message')->hasStudent() ? Column::make('Student Phone', 'student.phone')  : null,
            request()->route('message')->hasStaff() ? Column::make('Staff Phone', 'staff.phone')  : null,
        ]);
    } 

    protected function filename(): string
    {
        return 'Message Details_' . date('YmdHis');
    }
}
