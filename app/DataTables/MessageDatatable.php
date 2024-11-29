<?php

namespace App\DataTables;

use App\Models\Message;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class MessageDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('sent at', fn($message) => $message->created_at->toFormattedDateString())
            ->editColumn('send by', fn($message) => $message->createdBy->name)
            ->editColumn('type', fn($message) => view('components.datatables.message-type', compact('message')))
            ->editColumn('actions', function ($message) {
                return view('components.common.action-buttons', [
                    'model' => 'messages',
                    'id' => $message->id,
                    'buttons' => ['details'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Message $message)
    {
        return $message
            ->newQuery()
            ->when(request('type') == 'sms', fn($query) => $query->sms())
            ->when(request('type') == 'email', fn($query) => $query->email())
            ->when(request('type') == 'both (email and sms)', fn($query) => $query->both())
            ->select('messages.*')
            ->with([
                'createdBy:id,name',
            ])
            ->orderBy('created_at', 'DESC');
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('subject')->content('N/A'),
            Column::make('type')->orderable(false)->searchable(false)->content('N/A'),
            Column::make('sent at', 'created_at'),
            Column::make('send by', 'createdBy.name'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Messages_' . date('YmdHis');
    }
}
