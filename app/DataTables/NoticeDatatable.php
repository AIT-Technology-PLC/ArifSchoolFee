<?php

namespace App\DataTables;

use App\Models\Announcement;
use App\Models\Notice;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class NoticeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($notice) => route('notices.show', $notice->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('notice_date', fn($notice) => $notice->notice_date)
            ->editColumn('prepared by', fn($notice) => $notice->createdBy->name)
            ->editColumn('edited by', fn($notice) => $notice->updatedBy->name)
            ->editColumn('actions', function ($notice) {
                return view('components.common.action-buttons', [
                    'model' => 'notices',
                    'id' => $notice->id,
                    'buttons' => ['details'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Notice $notice)
    {
        return $notice
            ->newQuery()
            ->select('notices.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('title'),
            Column::make('notice_date'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Notices_' . date('YmdHis');
    }
}
