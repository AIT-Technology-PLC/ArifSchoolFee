<?php

namespace App\DataTables;

use App\Models\CustomField;
use App\Models\Transfer;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransferDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($transfer) => route('transfers.show', $transfer->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->customColumns('transfer')
            ->editColumn('branch', fn($transfer) => $transfer->warehouse->name)
            ->editColumn('status', fn($transfer) => view('components.datatables.transfer-status', compact('transfer')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'waiting approval', fn($query) => $query->notApproved())
                    ->when($keyword == 'approved', fn($query) => $query->approved()->notSubtracted())
                    ->when($keyword == 'subtracted', fn($query) => $query->subtracted()->notAdded())
                    ->when($keyword == 'added', fn($query) => $query->added());
            })
            ->editColumn('from', fn($transfer) => $transfer->transferredFrom->name)
            ->editColumn('to', fn($transfer) => $transfer->transferredTo->name)
            ->editColumn('description', fn($transfer) => view('components.datatables.searchable-description', ['description' => $transfer->description]))
            ->editColumn('issued_on', fn($transfer) => $transfer->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($transfer) => $transfer->createdBy->name)
            ->editColumn('approved by', fn($transfer) => $transfer->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($transfer) => $transfer->updatedBy->name)
            ->editColumn('actions', function ($transfer) {
                return view('components.common.action-buttons', [
                    'model' => 'transfers',
                    'id' => $transfer->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Transfer $transfer)
    {
        return $transfer
            ->newQuery()
            ->select('transfers.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('transfers.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->approved()->notSubtracted())
            ->when(request('status') == 'subtracted', fn($query) => $query->subtracted()->notAdded())
            ->when(request('status') == 'added', fn($query) => $query->added())
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'transferredFrom:id,name',
                'transferredTo:id,name',
                'warehouse:id,name',
                'customFieldValues.customField',
            ]);
    }

    protected function getColumns()
    {
        foreach (CustomField::active()->visibleOnColumns()->where('model_type', 'transfer')->pluck('label') as $label) {
            $customFields[] = Column::make($label, 'customFieldValues.value');
        }

        return Arr::whereNotNull([
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Transfer No'),
            Column::make('status')->orderable(false),
            Column::make('from', 'transferredFrom.name'),
            Column::make('to', 'transferredTo.name'),
            Column::make('description')->visible(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ]);
    }

    protected function filename(): string
    {
        return 'Transfer_' . date('YmdHis');
    }
}
