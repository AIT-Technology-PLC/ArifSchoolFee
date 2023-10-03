<?php

namespace App\DataTables;

use App\Models\Damage;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class DamageDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($damage) => route('damages.show', $damage->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($damage) => $damage->warehouse->name)
            ->editColumn('status', fn($damage) => view('components.datatables.damage-status', ['damage' => $damage]))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->notSubtracted()->approved())
                    ->when($keyword == 'subtracted', fn($query) => $query->subtracted());
            })
            ->editColumn('description', fn($damage) => view('components.datatables.searchable-description', ['description' => $damage->description]))
            ->editColumn('issued_on', fn($damage) => $damage->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($damage) => $damage->createdBy->name)
            ->editColumn('approved by', fn($damage) => $damage->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($damage) => $damage->updatedBy->name)
            ->editColumn('actions', function ($damage) {
                return view('components.common.action-buttons', [
                    'model' => 'damages',
                    'id' => $damage->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Damage $damage)
    {
        return $damage
            ->newQuery()
            ->select('damages.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('damages.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notSubtracted()->approved())
            ->when(request('status') == 'subtracted', fn($query) => $query->subtracted())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Damage No'),
            Column::make('status')->orderable(false),
            Column::make('description')->visible(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Damages_' . date('YmdHis');
    }
}
