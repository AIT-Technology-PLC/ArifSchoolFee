<?php

namespace App\DataTables;

use App\Models\Announcement;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AnnouncementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($announcement) => route('announcements.show', $announcement->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('status', fn($announcement) => view('components.datatables.announcement-status', compact('announcement')))
            ->editColumn('prepared by', fn($announcement) => $announcement->createdBy->name)
            ->editColumn('edited by', fn($announcement) => $announcement->updatedBy->name)
            ->editColumn('approved by', fn($announcement) => $announcement->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($announcement) {
                return view('components.common.action-buttons', [
                    'model' => 'announcements',
                    'id' => $announcement->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Announcement $announcement)
    {
        return $announcement
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('announcements.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Announcement No'),
            Column::computed('status'),
            Column::make('title'),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Announcement_' . date('YmdHis');
    }
}
