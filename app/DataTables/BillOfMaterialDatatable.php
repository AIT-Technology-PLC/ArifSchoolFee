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
            ->editColumn('customer', fn($billOfMaterial) => $billOfMaterial->customer->company_name ?? 'N/A')
            ->editColumn('prepared by', fn($billOfMaterial) => $billOfMaterial->createdBy->name)
            ->editColumn('approved by', fn($billOfMaterial) => $billOfMaterial->approvedBy->name ?? 'N/A')
            ->editColumn('active status', fn($billOfMaterial) => view('components.datatables.bill-of-material-is-active-status', compact('billOfMaterial')))
            ->editColumn('status', fn($billOfMaterial) => view('components.datatables.bill-of-material-status', compact('billOfMaterial')))
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
            ->when(request('activeStatus') == 'active', fn($query) => $query->active())
            ->when(request('activeStatus') == 'inactive', fn($query) => $query->inactive())
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approvale', fn($query) => $query->notApproved())
            ->select('bill_of_materials.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'product:id,name',
                'customer:id,company_name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('name'),
            Column::computed('status'),
            Column::make('product', 'product.name'),
            Column::make('customer', 'customer.company_name'),
            Column::computed('active status'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'BillOfMaterial_' . date('YmdHis');
    }
}
