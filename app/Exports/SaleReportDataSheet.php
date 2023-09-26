<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SaleReportDataSheet implements FromQuery, WithTitle, WithHeadings
{
    private $query;

    private $sheet;

    private $columns;

    public function __construct($query, $sheet)
    {
        $this->sheet = $sheet;

        $this->build($query);

        $this->columns = $this->query->columns;
    }

    public function query()
    {
        return $this->query;
    }

    public function title(): string
    {
        return $this->sheet;
    }

    public function headings(): array
    {
        return collect($this->columns)->map(fn($column) => str($column)->after('.')->after('AS ')->toString())->toArray();
    }

    private function build($query)
    {
        $this->query = $query
            ->orderBy('id')
            ->when(str($this->sheet)->is('master'), function ($q) use ($query) {
                $q->select([
                    $query->from . '.code AS ref_no',
                    $query->from . '.branch_name AS branch',
                    $query->from . '.user_name AS salesperson',
                    $query->from . '.customer_name',
                    $query->from . '.payment_type',
                    $query->from . '.cash_received',
                    $query->from . '.cash_received_type',
                    $query->from . '.issued_on',
                    $query->from . '.subtotal_price',
                ]);
            })
            ->when(str($this->sheet)->is('details'), function ($q) use ($query) {
                $q->select([
                    'code AS ref_no',
                    $query->from . '.product_category_name AS category',
                    $query->from . '.product_name AS product',
                    $query->from . '.quantity',
                    $query->from . '.product_unit_of_measurement AS unit',
                    $query->from . '.unit_price',
                    $query->from . '.line_price_before_tax',
                ]);
            })
            ->when(str($query->from)->containsAll(['gdn', 'detail']), function ($q) use ($query) {
                $q->addSelect([
                    $query->from . '.warehouse_name AS from',
                    $query->from . '.discount',
                ]);
            });
    }
}
