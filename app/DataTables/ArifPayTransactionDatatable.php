<?php

namespace App\DataTables;

use App\Models\ArifPayTransactionDetail;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Str;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ArifPayTransactionDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('student detail No', function ($arifPayTransaction) {
                return $arifPayTransaction->gdnDetail->id;
            })
            ->addIndexColumn();
    }

    public function query(ArifPayTransactionDetail $arifPayTransaction)
    {
        return $arifPayTransaction
            ->newQuery()
            ->select('arif_pay_transaction_details.*')
            ->with([
                'gdnDetail:id',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('student detail No', 'gdnDetail.id'),
            Column::make('session_id_number')->title('Transaction Number')->content('N/A'),
            Column::make('transaction_id')->title('Transaction ID')->content('N/A'),
            Column::make('transaction_status')->title('Status')->content('N/A'),
            Column::make('notification_url')->title('Notify Url')->content('N/A'),
            Column::make('uuid')->content('N/A'),
            Column::make('nonce')->content('N/A'),
            Column::make('phone')->content('N/A'),
            Column::make('total_amount')->content('N/A'),
        ];
    }

    protected function filename(): string
    {
        return 'ArifPayTransactions_' . date('YmdHis');
    }
}
