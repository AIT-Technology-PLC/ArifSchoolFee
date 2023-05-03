<?php

namespace App\DataTables;

use App\Models\PadField;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class TransactionFieldDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    private $transactionDetails;

    public function __construct()
    {
        $this->transactionDetails = request()->route('transaction')->transactionDetails;
    }

    public function dataTable($query)
    {
        $datatable = datatables()->collection($query->all());
        $padFields = PadField::inputTypeFile()->where('pad_id', request()->route('transaction')->pad_id)->get();

        foreach ($padFields as $padField) {
            $datatable->editColumn(str()->snake($padField->label), function ($row) use ($padField) {
                return view('components.datatables.link', [
                    'url' => isset($row[str()->snake($padField->label)]) ? asset('/storage/' . $row[str()->snake($padField->label)]) : '#',
                    'label' => isset($row[str()->snake($padField->label)]) ? $padField->label : ('No ' . $padField->label),
                    'target' => '_blank',
                ]);
            });

            if ($padField->isMerchandiseBatchField()) {
                $datatable->editColumn('expires_on', fn($row) => $row['expires_on'] ?? 'N/A');
            }
        }

        if (!request()->route('transaction')->pad->isInventoryOperationNone()) {
            $datatable
                ->editColumn('status', function ($row) {
                    return view('components.datatables.transaction-field-inventory-status', [
                        'transaction' => $row['transaction'],
                        'line' => $row['line'],
                    ]);
                });
        }

        return $datatable
            ->editColumn('actions', function ($row) {
                return view('components.common.transaction-actions', [
                    'model' => 'transaction-fields',
                    'id' => $row['id'],
                    'buttons' => ['delete', 'subtract', 'add'],
                    'transaction' => $row['transaction'],
                    'line' => $row['line'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query()
    {
        return $this->transactionDetails;
    }

    protected function getColumns()
    {
        $columns = [];

        $columns[] = Column::computed('#');

        $padFields = PadField::detailFields()->where('pad_id', request()->route('transaction')->pad_id)->get();

        foreach ($padFields as $padField) {
            $columns[] = Column::make(str()->snake($padField->label))->visible($padField->isVisible())->content('N/A');

            if ($padField->isMerchandiseBatchField()) {
                $columns[] = Column::make('expires_on')->visible(false);
            }
        }

        if (request()->route('transaction')->pad->hasPrices()) {
            $columns[] = Column::make('total');
        }

        $columns[] = !request()->route('transaction')->pad->isInventoryOperationNone() ? Column::computed('status') : '';

        $columns[] = Column::computed('actions')->className('actions');

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Transaction Details_' . date('YmdHis');
    }
}
