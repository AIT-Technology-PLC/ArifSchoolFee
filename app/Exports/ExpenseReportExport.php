<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ExpenseReportExport implements WithMultipleSheets
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
            'master' => new ExpenseReportDataSheet(clone $this->query, 'master'),
            'details' => new ExpenseReportDataSheet(clone $this->query, 'details'),
        ];
    }
}
