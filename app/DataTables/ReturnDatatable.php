<?php

namespace App\DataTables;

use App\Models\Returnn;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ReturnDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($return) => route('returns.show', $return->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($return) => $return->warehouse->name)
            ->editColumn('status', fn($return) => view('components.datatables.grn-status', ['grn' => $return]))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->notAdded()->approved())
                    ->when($keyword == 'added', fn($query) => $query->added());
            })
            ->editColumn('DO No', fn($return) => $return->gdn->code ?? 'N/A')
            ->editColumn('total price', function ($return) {
                return money($return->grandTotalPrice);
            })
            ->editColumn('customer', fn($return) => $return->customer->company_name ?? 'N/A')
            ->editColumn('customer', fn($return) => $return->customer->company_name ?? 'N/A')
            ->editColumn('customer_tin', fn($return) => $return->customer->tin ?? 'N/A')
            ->editColumn('description', fn($return) => view('components.datatables.searchable-description', ['description' => $return->description]))
            ->editColumn('issued_on', fn($return) => $return->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($return) => $return->createdBy->name)
            ->editColumn('approved by', fn($return) => $return->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($return) => $return->updatedBy->name)
            ->editColumn('actions', function ($return) {
                return view('components.common.action-buttons', [
                    'model' => 'returns',
                    'id' => $return->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Returnn $return)
    {
        return $return
            ->newQuery()
            ->select('returns.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('returns.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->notAdded()->approved())
            ->when(request('status') == 'added', fn($query) => $query->added())
            ->with([
                'returnDetails',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'customer:id,company_name,tin',
                'warehouse:id,name',
                'gdn:id,code',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Return No'),
            Column::make('DO No', 'gdn.code')->visible(false),
            Column::make('status')->orderable(false),
            Column::computed('total price')->visible(false),
            Column::make('customer', 'customer.company_name'),
            Column::make('customer_tin', 'customer.tin')->visible(false)->title('Customer TIN'),
            Column::make('description')->visible(false),
            Column::make('issued_on'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Return_' . date('YmdHis');
    }
}
