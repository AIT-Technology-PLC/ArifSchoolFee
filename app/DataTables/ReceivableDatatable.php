<?php

namespace App\DataTables;

use Yajra\DataTables\Services\DataTable;

class ReceivableDatatable extends DataTable
{

    protected function filename()
    {
        return 'Receivable_' . date('YmdHis');
    }
}
