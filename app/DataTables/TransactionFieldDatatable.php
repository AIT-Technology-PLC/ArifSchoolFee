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
        return datatables()
            ->collection($query->all())
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
        return $this->transactionDetails;
    }

    protected function getColumns()
    {
        $columns = [];

        $columns[] = Column::computed('#');

        $padFields = PadField::detailFields()->where('pad_id', request()->route('transaction')->pad_id)->get();

        foreach ($padFields as $padField) {
            $columns[] = Column::make(str()->snake($padField->label))->visible($padField->isVisible())->content('N/A');
        }

        if (request()->route('transaction')->pad->hasPrices()) {
            $columns[] = userCompany()->isDiscountBeforeVAT() && $padFields->contains('label', 'discount') ? Column::computed('discount') : null;
            $columns[] = Column::make('total');
        }

        $columns[] = Column::computed('actions')->className('actions');

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename()
    {
        return 'Transaction Details_' . date('YmdHis');
    }
}
