<?php

namespace App\DataTables;

use App\Models\PadField;
use App\Models\Transaction;
use App\Models\TransactionField;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $padFields;

    private $datatable;

    public function __construct()
    {
        $this->padFields = PadField::query()
            ->where('pad_id', request()->route('pad')->id)
            ->where('is_master_field', 1)
            ->where('is_visible', 1)
            ->get();
    }

    public function dataTable($query)
    {
        $this->addDynamicColumns($query);

        return $this
            ->datatable
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($transaction) => route('transactions.show', $transaction->id),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ])
            ->editColumn('branch', fn($transaction) => $transaction->warehouse->name)
            ->editColumn('issued_on', fn($transaction) => $transaction->issued_on->toFormattedDateString())
            ->editColumn('prepared by', fn($transaction) => $transaction->createdBy->name)
            ->editColumn('edited by', fn($transaction) => $transaction->updatedBy->name)
            ->editColumn('actions', function ($transaction) {
                return view('components.common.action-buttons', [
                    'model' => 'transactions',
                    'id' => $transaction->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Transaction $transaction)
    {
        return $transaction
            ->newQuery()
            ->where('pad_id', request()->route('pad')->id)
            ->when(is_numeric(request('branch')), fn($query) => $query->where('transactions.warehouse_id', request('branch')))
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'warehouse:id,name',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Reference No'),
            request()->route('pad')->hasStatus() ? Column::computed('status') : '',
        ];

        foreach ($this->padFields as $padField) {
            $columns[] = Column::computed($padField->label);
        }

        $moreColumns = [
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        array_push($columns, ...$moreColumns);

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Transactions_' . date('YmdHis');
    }

    private function addDynamicColumns($query)
    {
        $datatable = datatables()->eloquent($query);

        foreach ($this->padFields as $padField) {
            $datatable
                ->editColumn($padField->label, function ($transaction) use ($padField) {
                    return TransactionField::query()
                        ->where('pad_field_id', $padField->id)
                        ->where('transaction_id', $transaction->id)
                        ->first()
                        ->value ?? 'N/A';
                });
        }

        $this->datatable = $datatable;
    }
}
