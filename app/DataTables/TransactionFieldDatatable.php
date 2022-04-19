<?php

namespace App\DataTables;

use App\Models\PadField;
use App\Models\TransactionField;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionFieldDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $padFields;

    private $datatable;

    public function __construct()
    {
        $this->padFields = PadField::query()
            ->where('pad_id', request()->route('transaction')->pad->id)
            ->where('is_master_field', 0)
            ->where('is_visible', 1)
            ->get();
    }

    public function dataTable($query)
    {
        $this->addDynamicColumns($query);

        return $this
            ->datatable
            ->editColumn('actions', function ($row) {
                return view('components.common.action-buttons', [
                    'model' => 'transaction-fields',
                    'id' => $row['id'],
                    'buttons' => ['delete'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query()
    {
        $newTransactionFields = [];
        $row = 0;

        $groupedTransactionFields = TransactionField::query()
            ->where('transaction_id', request()->route('transaction')->id)
            ->whereNotNull('line')
            ->with(['padField'])
            ->get()
            ->groupBy('line');

        foreach ($groupedTransactionFields as $key => $transactionFields) {
            foreach ($transactionFields as $transactionField) {
                $newTransactionFields[$row][$transactionField->padField->label] = $transactionField->value ?? 'N/A';
                $newTransactionFields[row]['id'] = $transactionField->id;
            }

            $row++;
        }

        return collect($newTransactionFields);
    }

    protected function getColumns()
    {
        foreach ($this->padFields as $padField) {
            $columns[] = Column::computed($padField->label);
        }

        Arr::prepend($columns, Column::computed('#'));

        $columns[] = Column::computed('actions')->className('actions');

        return $columns;
    }

    protected function filename()
    {
        return 'Transaction Details_' . date('YmdHis');
    }

    private function addDynamicColumns($query)
    {
        $datatable = datatables()->collection($query->all());

        $this
            ->padFields
            ->each(function ($padField) use ($datatable) {
                $datatable
                    ->editColumn($padField->label, function ($row) use ($padField) {
                        return $row[$padField->label];
                    });
            });

        $this->datatable = $datatable;
    }
}
