<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class ReportSource
{
    public static function getSalesReportInput($filters)
    {
        [$masterTable, $detailsTable, $status] = match(userCompany()->sales_report_source) {
            'All Delivery Orders' => ['gdn_master_reports', 'gdn_detail_reports', null],
            'Approved & Subtracted Delivery Orders' => ['gdn_master_reports', 'gdn_detail_reports', ['approved', 'subtracted']],
            'Subtracted Delivery Orders' => ['gdn_master_reports', 'gdn_detail_reports', ['subtracted']],
            'All Invoices' => ['sale_master_reports', 'sale_detail_reports', null],
            'Approved Invoices' => ['sale_master_reports', 'sale_detail_reports', ['approved']],
        };

        return [
            'base' => DB::table($masterTable)
                ->where('company_id', userCompany()->id)
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status)),

            'master' => DB::table($masterTable)
                ->where('company_id', userCompany()->id)
                ->when(isset($filters['branches']), fn($q) => $q->whereIn($masterTable . '.warehouse_id', $filters['branches']))
                ->when(isset($filters['period']), fn($q) => $q->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1]))
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when(isset($filters['user_id']), fn($query) => $query->where($masterTable . '.created_by', $filters['user_id']))
                ->when(isset($filters['customer_id']), fn($query) => $query->where($masterTable . '.customer_id', $filters['customer_id']))
                ->when(
                    isset($filters['product_id']),
                    fn($q) => $q
                        ->whereIn('id', fn($query) => $query
                                ->select('gdn_id')
                                ->from($detailsTable)
                                ->where($detailsTable . '.product_id', $filters['product_id'])
                        )
                ),

            'details' => DB::table($detailsTable)
                ->join(
                    $masterTable,
                    str($detailsTable)->append('.', str($detailsTable)->before('_')->append('_id')),
                    str($masterTable)->append('.id'))
                ->where('company_id', userCompany()->id)
                ->when(isset($filters['branches']), fn($q) => $q->whereIn($masterTable . '.warehouse_id', $filters['branches']))
                ->when(isset($filters['period']), fn($q) => $q->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1]))
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when(isset($filters['user_id']), fn($query) => $query->where($masterTable . '.created_by', $filters['user_id']))
                ->when(isset($filters['customer_id']), fn($query) => $query->where($masterTable . '.customer_id', $filters['customer_id']))
                ->when(isset($filters['product_id']), fn($query) => $query->where($detailsTable . '.product_id', $filters['product_id'])),
        ];
    }
}
