<?php

namespace App\DataTables;

use App\Models\User;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Notifications\DatabaseNotification as Notification;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NotificationDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($notification) => route('notifications.show', $notification->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('notification', fn($notification) => $notification->data['message'])
            ->editColumn('status', fn($notification) => view('components.datatables.notification-status', compact('notification')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'seen', fn($query) => $query->read())
                    ->when($keyword == 'unseen', fn($query) => $query->unread());
            })
            ->editColumn('created_at', fn($notification) => $notification->created_at->toDayDateTimeString())
            ->editColumn('read_at', fn($notification) => $notification->read_at ? $notification->read_at->toDayDateTimeString() : '')
            ->editColumn('actions', function ($notification) {
                return view('components.datatables.notification-action', compact('notification'));
            })
            ->addIndexColumn();
    }

    public function query(Notification $notification)
    {
        return $notification
            ->newQuery()
            ->whereMorphRelation('notifiable', User::class, 'notifiable_id', authUser()->id)
            ->select('notifications.*')
            ->when(request('status') == 'seen', fn($query) => $query->read())
            ->when(request('status') == 'unseen', fn($query) => $query->unread());
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('notification', 'data->message'),
            Column::make('status')->orderable(false),
            Column::make('read_at')->className('has-text-right')->visible(false),
            Column::make('created_at')->className('has-text-right')->title('Received On'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Notifications_' . date('YmdHis');
    }
}
