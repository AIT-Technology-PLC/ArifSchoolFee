<?php

namespace App\DataTables;

use App\Models\AcademicYear;
use App\Models\Section;
use App\Models\Vehicle;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class VehicleDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('created at', fn($vehicle) => $vehicle->created_at->toFormattedDateString())
            ->editColumn('created by', fn($vehicle) => $vehicle->createdBy->name)
            ->editColumn('edited by', fn($vehicle) => $vehicle->updatedBy->name)
            ->editColumn('note', fn($vehicle) => view('components.datatables.searchable-description', ['description' => $vehicle->note]))
            ->editColumn('actions', function ($vehicle) {
                return view('components.common.action-buttons', [
                    'model' => 'vehicles',
                    'id' => $vehicle->id,
                    'buttons' => ['edit','delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Vehicle $vehicle)
    {
        return $vehicle
            ->newQuery()
            ->select('vehicles.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('vehicle_number')->addClass('has-text-weight-bold'),
            Column::make('vehicle_model'),
            Column::make('year_made')->content('N/A'),
            Column::make('driver_name'),
            Column::make('driver_phone'),
            Column::make('note')->content('N/A')->visible(false),
            Column::make('created by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('created at', 'created_at'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Vehicles_' . date('YmdHis');
    }
}
