<?php

namespace App\DataTables;

use App\Models\PriceIncrement;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class PriceIncrementDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($priceIncrement) => route('price-increments.show', $priceIncrement->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('code', fn($priceIncrement) => $priceIncrement->code)
            ->editColumn('status', fn($priceIncrement) => view('components.datatables.price-increment-status', compact('priceIncrement')))
            ->editColumn('price_type', fn($priceIncrement) => $priceIncrement->price_type)
            ->editColumn('price_increment', fn($priceIncrement) => $priceIncrement->price_increment)
            ->editColumn('target_product', fn($priceIncrement) => $priceIncrement->target_product)
            ->editColumn('prepared by', fn($priceIncrement) => $priceIncrement->createdBy->name)
            ->editColumn('approved by', fn($priceIncrement) => $priceIncrement->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($priceIncrement) => $priceIncrement->updatedBy->name)
            ->editColumn('actions', function ($priceIncrement) {
                return view('components.common.action-buttons', [
                    'model' => 'price-increments',
                    'id' => $priceIncrement->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(PriceIncrement $priceIncrement)
    {
        return $priceIncrement
            ->newQuery()
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->select('price_increments.*')
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
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            Column::computed('status'),
            Column::make('target_product'),
            Column::computed('price_type'),
            Column::make('price_increment'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'PriceIncrement_' . date('YmdHis');
    }
}