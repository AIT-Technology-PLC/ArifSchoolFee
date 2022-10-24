<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SaleReportExport implements WithMultipleSheets
{
    use Exportable;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function sheets(): array
    {
        return [
            'master' => new SaleReportDataSheet($this->query['master'], 'master'),
            'details' => new SaleReportDataSheet($this->query['details'], 'details'),
        ];
    }
}
