<?php

namespace App\DataTables;

use App\Models\BillOfMaterial;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class BillOfMaterialDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($billOfMaterial) => route('bill-of-materials.show', $billOfMaterial->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])

            ->editColumn('name', fn($billOfMaterial) => $billOfMaterial->name)
            ->editColumn('product', fn($billOfMaterial) => $billOfMaterial->product->name)
            ->editColumn('prepared by', fn($billOfMaterial) => $billOfMaterial->createdBy->name)
            ->editColumn('edited by', fn($billOfMaterial) => $billOfMaterial->updatedBy->name)
            ->editColumn('actions', function ($billOfMaterial) {
                return view('components.common.action-buttons', [
                    'model' => 'bill-of-materials',
                    'id' => $billOfMaterial->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(BillOfMaterial $billOfMaterial)
    {
        return $billOfMaterial
            ->newQuery()
            ->select('bill_of_materials.*')
            ->with([
                'billOfMaterialDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'product:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('name'),
            Column::make('product', 'product.name'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'BillOfMaterial_' . date('YmdHis');
    }
}