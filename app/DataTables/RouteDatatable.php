<?php

namespace App\DataTables;

use App\Models\Route;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class RouteDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fare', fn($route) => money($route->fare))
            ->editColumn('vehicles', fn($route) => implode(', ', $route->vehicles->pluck('vehicle_number')->toArray()))
            ->editColumn('created at', fn($route) => $route->created_at->toFormattedDateString())
            ->editColumn('created by', fn($route) => $route->createdBy->name)
            ->editColumn('edited by', fn($route) => $route->updatedBy->name)
            ->editColumn('actions', function ($route) {
                return view('components.common.action-buttons', [
                    'model' => 'routes',
                    'id' => $route->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Route $route)
    {
        return $route
            ->newQuery()
            ->select('routes.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'vehicles:id,vehicle_number',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('title')->addClass('has-text-weight-bold'),
            Column::make('fare'),
            Column::make('vehicles', 'vehicles.vehicle_number'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('created at', 'created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Routes_' . date('YmdHis');
    }
}
