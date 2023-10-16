<?php

namespace App\DataTables;

use App\Models\Advancement;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AdvancementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($advancement) => route('advancements.show', $advancement->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('issued_on', fn($advancement) => $advancement->issued_on->toFormattedDateString())
            ->editColumn('branch', fn($advancement) => $advancement->warehouse->name)
            ->editColumn('status', fn($advancement) => view('components.datatables.advancement-status', compact('advancement')))
            ->editColumn('type', fn($advancement) => $advancement->type)
            ->editColumn('description', fn($advancement) => $advancement->description ?? 'N/A')
            ->editColumn('prepared by', fn($advancement) => $advancement->createdBy->name ?? 'N/A')
            ->editColumn('approved by', fn($advancement) => $advancement->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($advancement) {
                return view('components.common.action-buttons', [
                    'model' => 'advancements',
                    'id' => $advancement->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Advancement $advancement)
    {
        return $advancement
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('advancements.*')
            ->with([
                'createdBy:id,name',
                'approvedBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('code')->className('has-text-centered')->title('Advancement No'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::computed('status'),
            Column::make('issued_on'),
            Column::make('type'),
            Column::make('description')->visible(false),
            Column::make('prepared by', 'createdBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }
    protected function filename(): string
    {
        return 'Advancement_' . date('YmdHis');
    }
}
