<?php

namespace App\DataTables;

use App\Models\FeeMaster;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class FeeMasterDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('fee group', fn($feeMaster) => $feeMaster->feeType->feeGroup->name)
            ->editColumn('fee type', fn($feeMaster) => $feeMaster->feeType->name)
            ->editColumn('created_at', fn($feeMaster) => $feeMaster->created_at->toFormattedDateString())
            ->editColumn('added by', fn($feeMaster) => $feeMaster->createdBy->name ?? 'N/A')
            ->editColumn('edited by', fn($feeMaster) => $feeMaster->updatedBy->name ?? 'N/A')
            ->editColumn('actions', function ($feeMaster) {
                return view('components.datatables.fee-master-action', compact('feeMaster'));
            })
            ->addIndexColumn();
    }

    public function query(FeeMaster $feeMaster)
    {
        return $feeMaster
            ->newQuery()
            ->select('fee_masters.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'feeType',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->title('Fee No'),
            Column::make('fee group')->content('N/A')->searchable(false),
            Column::make('fee type', 'feeType.name')->content('N/A'),
            Column::make('amount'),
            Column::make('due_date'),
            Column::make('fine_type')->content('N/A'),
            Column::make('fine_amount')->content('N/A'),
            Column::make('created_at')->className('has-text-right')->visible(false),
            Column::make('added by', 'createdBy.name')->visible(false),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Fee Masters_' . date('YmdHis');
    }
}
