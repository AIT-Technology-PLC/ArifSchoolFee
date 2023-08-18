<?php

namespace App\DataTables;

use App\Models\Warehouse;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class WarehouseDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('is_sales_store', fn ($warehouse) => $warehouse->is_sales_store ? 'Sales Store' : 'Main Store')
            ->editColumn('can_be_sold_from', fn ($warehouse) => $warehouse->can_be_sold_from ? 'Yes' : 'No')
            ->editColumn('pos_provider', fn ($warehouse) => $warehouse->pos_provider ?? 'N/A')
            ->editColumn('host_address', fn ($warehouse) => $warehouse->host_address ?? 'N/A')
            ->editColumn('status', fn ($warehouse) => view('components.datatables.warehouse-status', compact('warehouse')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'active', fn ($query) => $query->active())
                    ->when($keyword == 'inactive', fn ($query) => $query->inactive());
            })
            ->editColumn('description', fn ($warehouse) => view('components.datatables.searchable-description', ['description' => $warehouse->description]))
            ->editColumn('created on', fn ($warehouse) => $warehouse->created_at->toFormattedDateString())
            ->editColumn('created by', fn ($warehouse) => $warehouse->createdBy->name)
            ->editColumn('edited by', fn ($warehouse) => $warehouse->updatedBy->name)
            ->editColumn('actions', function ($warehouse) {
                return view('components.common.action-buttons', [
                    'model' => 'warehouses',
                    'id' => $warehouse->id,
                    'buttons' => ['edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Warehouse $warehouse)
    {
        return $warehouse
            ->newQuery()
            ->when(request('status') == 'active', fn ($query) => $query->active())
            ->when(request('status') == 'inactive', fn ($query) => $query->inactive())
            ->select('warehouses.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return collect([
            Column::computed('#'),
            Column::make('name')->addClass('text-green has-text-weight-bold'),
            Column::make('location'),
            Column::make('is_sales_store')->title('Type')->searchable(false),
            Column::make('can_be_sold_from')->searchable(false)->addClass('has-text-centered')->visible(false),
            userCompany()->hasIntegration('Point of Sale') ? Column::make('pos_provider')->visible(false)->title('Point of Sale Provider') : null,
            userCompany()->hasIntegration('Point of Sale') ? Column::make('host_address')->visible(false)->title('Point of Sale Provider Host') : null,
            Column::make('status')->orderable(false),
            Column::make('email')->content('N/A')->visible(false),
            Column::make('phone')->content('N/A')->visible(false),
            Column::make('description')->visible(false),
            Column::make('created on', 'created_at')->className('has-text-right'),
            Column::make('created by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ])
            ->filter()
            ->toArray();
    }

    protected function filename()
    {
        return 'Warehouses_'.date('YmdHis');
    }
}
