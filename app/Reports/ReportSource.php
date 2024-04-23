<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class ReportSource
{
    public static function getSalesReportInput($filters, $company = null)
    {
        $company = $company ?? userCompany();

        $source = $filters['source'] ?? $company->sales_report_source;

        [$masterTable, $detailsTable] = match ($source) {
            'Delivery Orders' => ['gdn_master_reports', 'gdn_detail_reports'],
            'Invoices' => ['sale_master_reports', 'sale_detail_reports'],
        };

        return [
            'base' => DB::table($masterTable)
                ->where('company_id', $company->id),

            'master' => DB::table($masterTable)
                ->where('company_id', $company->id)
                ->when(isset($filters['branches']), fn($q) => $q->whereIn($masterTable . '.warehouse_id', $filters['branches']))
                ->when(isset($filters['period']), fn($q) => $q->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1]))
                ->when(isset($filters['user_id']), fn($query) => $query->where($masterTable . '.created_by', $filters['user_id']))
                ->when(isset($filters['customer_id']), fn($query) => $query->where($masterTable . '.customer_id', $filters['customer_id']))
                ->when(isset($filters['payment_method']), fn($query) => $query->where($masterTable . '.payment_type', $filters['payment_method']))
                ->when(isset($filters['bank_name']), fn($query) => $query->where($masterTable . '.bank_name', $filters['bank_name']))
                ->when(
                    isset($filters['product_id']),
                    fn($q) => $q
                        ->whereIn('id', fn($query) => $query
                                ->select('master_id')
                                ->from($detailsTable)
                                ->where($detailsTable . '.product_id', $filters['product_id'])
                        )
                ),

            'details' => DB::table($detailsTable)
                ->join(
                    $masterTable,
                    str($detailsTable)->append('.', str($detailsTable)->before('_')->append('_id')),
                    str($masterTable)->append('.id'))
                ->where('company_id', $company->id)
                ->when(isset($filters['branches']), fn($q) => $q->whereIn($masterTable . '.warehouse_id', $filters['branches']))
                ->when(isset($filters['period']), fn($q) => $q->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1]))
                ->when(isset($filters['user_id']), fn($query) => $query->where($masterTable . '.created_by', $filters['user_id']))
                ->when(isset($filters['customer_id']), fn($query) => $query->where($masterTable . '.customer_id', $filters['customer_id']))
                ->when(isset($filters['product_id']), fn($query) => $query->where($detailsTable . '.product_id', $filters['product_id']))
                ->when(isset($filters['payment_method']), fn($query) => $query->where($masterTable . '.payment_type', $filters['payment_method']))
                ->when(isset($filters['bank_name']), fn($query) => $query->where($masterTable . '.bank_name', $filters['bank_name'])),
        ];
    }
}
