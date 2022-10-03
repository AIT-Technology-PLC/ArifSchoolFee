<?php

namespace App\Reports;

use Illuminate\Support\Facades\DB;

class ReportSource
{
    public static function getSalesReportInput($branches, $period, $userId = null)
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
                ->whereIn($masterTable . '.warehouse_id', $branches)
                ->whereDate($masterTable . '.issued_on', '>=', $period[0])->whereDate($masterTable . '.issued_on', '<=', $period[1])
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when(!is_null($userId), fn($query) => $query->where($masterTable . '.created_by', $userId)),

            'details' => DB::table($detailsTable)
                ->join(
                    $masterTable,
                    str($detailsTable)->append('.', str($detailsTable)->before('_')->append('_id')),
                    str($masterTable)->append('.id'))
                ->where('company_id', userCompany()->id)
                ->whereIn($masterTable . '.warehouse_id', $branches)
                ->whereDate($masterTable . '.issued_on', '>=', $period[0])->whereDate($masterTable . '.issued_on', '<=', $period[1])
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status))
                ->when(!is_null($userId), fn($query) => $query->where($masterTable . '.created_by', $userId)),
        ];
    }
}