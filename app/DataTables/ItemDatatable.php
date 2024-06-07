<?php

namespace App\DataTables;

use App\Models\Item;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ItemDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('added on', fn($item) => $item->created_at->toFormattedDateString())
            ->editColumn('added on', fn($item) => $item->created_at->toFormattedDateString())
            ->editColumn('created by', fn($item) => $item->createdBy->name)
            ->editColumn('edited by', fn($item) => $item->updatedBy->name)
            ->editColumn('actions', function ($item) {
                return view('components.common.action-buttons', [
                    'model' => 'items',
                    'id' => $item->id,
                    'buttons' => ['edit', 'delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Item $item)
    {
        return $item
            ->newQuery()
            ->select('items.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name')->addClass('text-green has-text-weight-bold'),
            Column::make('description')->visible(false),
            Column::make('added on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Item_' . date('YmdHis');
    }
}
