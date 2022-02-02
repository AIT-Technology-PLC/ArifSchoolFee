<?php

namespace App\DataTables;

use App\Models\Siv;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SivDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($siv) => route('sivs.show', $siv->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($siv) => $siv->warehouse->name)
            ->editColumn('status', fn($siv) => view('components.datatables.siv-status', compact('siv')))
            ->filterColumn('status', function ($query, $keyword) {
                $query
                    ->when($keyword == 'approved', fn($query) => $query->approved())
                    ->when($keyword == 'waiting-approval', fn($query) => $query->notApproved());
            })
            ->editColumn('issued_to', fn($siv) => $siv->issued_to ?: 'N/A')
            ->editColumn('purpose', function ($siv) {
                if (!$siv->purpose) {
                    return 'N/A';
                }

                return $siv->purpose . ($siv->ref_num ? ' No: ' . $siv->ref_num : '');
            })
            ->editColumn('description', fn($siv) => view('components.datatables.searchable-description', ['description' => $siv->description]))
            ->editColumn('issued_on', fn($siv) => $siv->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($siv) => $siv->createdBy->name)
            ->editColumn('approved by', fn($siv) => $siv->approvedBy->name ?? 'N/A')
            ->editColumn('edited by', fn($siv) => $siv->updatedBy->name)
            ->editColumn('actions', function ($siv) {
                return view('components.common.action-buttons', [
                    'model' => 'sivs',
                    'id' => $siv->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Siv $siv)
    {
        return $siv
            ->newQuery()
            ->select('sivs.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('sivs.warehouse_id', request('branch')))
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->when(request('status') == 'approved', fn($query) => $query->approved())
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
            Column::make('code')->className('has-text-centered')->title('SIV No'),
            Column::make('status')->orderable(false),
            Column::make('issued_to')->title('Customer'),
            Column::make('purpose'),
            Column::make('received_by')
                ->title('Receiver')
                ->className('is-capitalized')
                ->content('N/A')
                ->visible(false),
            Column::make('description')->visible(false),
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename()
    {
        return 'Store Issue Vouchers_' . date('YmdHis');
    }
}
