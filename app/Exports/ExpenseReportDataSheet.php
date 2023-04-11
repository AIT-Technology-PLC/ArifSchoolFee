<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class ExpenseReportDataSheet implements FromQuery, WithTitle, WithHeadings
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
            ->orderBy('expenses.id')
            ->when(str($this->sheet)->is('master'), function ($q) {
                $q->select([
                    'expenses.code AS expense_no',
                    'warehouses.name AS branch',
                    'users.name AS user_name',
                    'suppliers.company_name AS supplier',
                    'taxes.type AS tax_type',
                    'expenses.reference_number',
                    'expenses.issued_on',
                    'expenses.payment_type',
                ])->selectRaw(
                    <<<'QUERY'
                        ROUND((SELECT SUM(ed.unit_price*ed.quantity)
                        FROM expense_details ed
                        WHERE ed.expense_id = expenses.id AND ed.deleted_at IS NULL
                        GROUP BY ed.expense_id), 2) AS subtotal_price
                    QUERY
                )->distinct('expense_id');
            })
            ->when(str($this->sheet)->is('details'), function ($q) {
                $q->select([
                    'expenses.code AS expense_no',
                    'expense_categories.name AS category',
                    'expense_details.name',
                    'expense_details.quantity',
                    'expense_details.unit_price',
                ]);
            });
    }
}