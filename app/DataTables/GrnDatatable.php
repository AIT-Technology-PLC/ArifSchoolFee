<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Grn;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class GrnDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($grn) => route('grns.show', $grn->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->customColumns('grn')
            ->editColumn('branch', fn($grn) => $grn->warehouse->name)
            ->editColumn('purchase no', fn($grn) => $grn->purchase->code ?? 'N/A')
            ->editColumn('status', fn($grn) => view('components.datatables.grn-status', compact('grn')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->notAdded()->approved())
                    ->when($keyword == 'added', fn($query) => $query->added());
            })
            ->editColumn('supplier', fn($grn) => $grn->supplier->company_name ?? 'N/A')
            ->editColumn('description', fn($grn) => view('components.datatables.searchable-description', ['description' => $grn->description]))
            ->editColumn('issued_on', fn($grn) => $grn->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($grn) => $grn->createdBy->name)
            ->editColumn('approved by', fn($grn) => $grn->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($grn) => $grn->updatedBy->name)
            ->editColumn('actions', function ($grn) {
                return view('components.common.action-buttons', [
                    'model' => 'grns',
                    'id' => $grn->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Grn $grn)
    {
        return $grn
            ->newQuery()
            ->select('grns.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('grns.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notAdded()->approved())
            ->when(request('status') == 'added', fn($query) => $query->added())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'purchase:id,code',
                'supplier:id,company_name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'grn')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('GRN No'),
            isFeatureEnabled('Purchase Management') ? Column::make('purchase no', 'purchase.code')->visible(false) : null,
            ...($customFields ?? []),
            Column::make('status')->orderable(false),
            Column::make('supplier', 'supplier.company_name'),
            Column::make('description')->visible(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Goods Received Notes_' . date('YmdHis');
    }
}
