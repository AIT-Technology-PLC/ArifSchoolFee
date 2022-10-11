<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ReturnReportExport implements WithMultipleSheets
{
    use Exportable;

    private $query;

    public function __construct($query)
    {
        $this->query = $query;
    }

    public function sheets(): array
    {
        return [
            'master' => new ReturnReportDataSheet(clone $this->query, 'master'),
            'details' => new ReturnReportDataSheet(clone $this->query, 'details'),
        ];
    }
}
