<?php

namespace App\DataTables;

use App\Models\PadField;
use App\Models\Transaction;
use App\Models\TransactionField;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
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
            ->masterFields()
            ->where('is_visible', 1)
            ->get();
    }

    public function dataTable($query)
    {
        $datatable = datatables()
            ->collection($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn ($transaction) => route('transactions.show', $transaction['id']),
                'x-data' => 'showRowDetails',
                '@click' => 'showDetails',
            ]);

        if (request()->route('pad')->hasStatus() || request()->route('pad')->isClosableOnly()) {
            $datatable
                ->editColumn('status', function ($transaction) {
                    return view('components.datatables.transaction-status', [
                        'transaction' => Transaction::find($transaction['id']),
                    ]);
                });
        }

        return $datatable
            ->editColumn('actions', function ($transaction) {
                return view('components.common.action-buttons', [
                    'model' => 'transactions',
                    'id' => $transaction['id'],
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query()
    {
        return Transaction::query()
            ->where('pad_id', request()->route('pad')->id)
            ->when(is_numeric(request('branch')), fn ($query) => $query->where('transactions.warehouse_id', request('branch')))
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
                'warehouse:id,name',
            ])
            ->get()
            ->map(function ($transaction) {
                $data = [];

                $data['id'] = $transaction->id;
                $data['branch'] = $transaction->warehouse->name;
                $data['code'] = $transaction->code;
                $data['issued_on'] = $transaction->issued_on->toDateTimeString();
                $data['prepared by'] = $transaction->createdBy->name;
                $data['edited by'] = $transaction->updatedBy->name;

                foreach ($this->padFields as $padField) {
                    $value = TransactionField::query()
                        ->where('pad_field_id', $padField->id)
                        ->where('transaction_id', $transaction->id)
                        ->first()
                        ->value ?? null;

                    if ($value && $padField->hasRelation()) {
                        $value = DB::table(
                            str($padField->padRelation->model_name)->plural()->lower()
                        )->find($value)->{$padField->padRelation->representative_column};
                    }

                    if ($value && str_contains($padField->tag_type, 'date')) {
                        $value = (new Carbon($value))->toDayDateTimeString();
                    }

                    $data[$padField->label] = $value ?? 'N/A';
                }

                return $data;
            });
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch')->visible(false),
            Column::make('code')->className('has-text-centered')->title(request()->route('pad')->abbreviation.' No'),
            (request()->route('pad')->hasStatus() || request()->route('pad')->isClosableOnly()) ? Column::computed('status') : '',
        ];

        foreach ($this->padFields as $padField) {
            $columns[] = Column::make($padField->label);
        }

        $moreColumns = [
            Column::make('issued_on')->className('has-text-right'),
            Column::make('prepared by'),
            Column::make('edited by')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        array_push($columns, ...$moreColumns);

        return Arr::where($columns, fn ($column) => $column != null);
    }

    protected function filename()
    {
        return 'Transactions_'.date('YmdHis');
    }
}
