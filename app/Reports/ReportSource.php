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
            'master' => DB::table($masterTable)
                ->where('company_id', userCompany()->id)
                ->whereIn($masterTable . '.warehouse_id', $filters['branches'])
                ->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1])
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when($filters['user_id'] ?? false, fn($query) => $query->where($masterTable . '.created_by', $filters['user_id'])),

            'details' => DB::table($detailsTable)
                ->join(
                    $masterTable,
                    str($detailsTable)->append('.', str($detailsTable)->before('_')->append('_id')),
                    str($masterTable)->append('.id'))
                ->where('company_id', userCompany()->id)
                ->whereIn($masterTable . '.warehouse_id', $filters['branches'])
                ->whereDate($masterTable . '.issued_on', '>=', $filters['period'][0])->whereDate($masterTable . '.issued_on', '<=', $filters['period'][1])
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when($filters['user_id'] ?? false, fn($query) => $query->where($masterTable . '.created_by', $filters['user_id'])),
        ];
    }
}
