<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReturnReportDataSheet implements FromQuery, WithTitle, WithHeadings
{
    private $query;

    private $sheet;

    private $columns;

    public function __construct($query, $sheet)
    {
        $this->sheet = $sheet;

        $this->build($query);

        $this->columns = $this->query->getQuery()->columns;
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
            ->orderBy('returns.code')
            ->when(str($this->sheet)->is('master'), function ($q) {
                $q->select([
                    'returns.code AS return_no',
                    'warehouses.name AS branch',
                    'customers.company_name AS customer',
                    'returns.issued_on',
                ])->selectRaw(
                    <<<'QUERY'
                        ROUND((SELECT SUM(rd.unit_price*rd.quantity)
                        FROM return_details rd
                        WHERE rd.return_id = returns.id AND rd.deleted_at IS NULL
                        GROUP BY rd.return_id), 2) AS subtotal_price
                    QUERY
                )->distinct('return_id');
            })
            ->when(str($this->sheet)->is('details'), function ($q) {
                $q->select([
                    'returns.code AS return_no',
                    'products.name AS product',
                    'return_details.quantity',
                    'products.unit_of_measurement AS unit',
                    'return_details.unit_price',
                    'warehouses.name AS from',
                ]);
            });
    }
}
