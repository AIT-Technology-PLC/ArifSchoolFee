<?php

namespace App\Reports;

use App\Models\Expense;
use App\Models\ReturnDetail;
use App\Scopes\BranchScope;
use Illuminate\Support\Facades\DB;

class ReportSource
{
    public static function getSalesReportInput($branches, $period)
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
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status)),

            'details' => DB::table($detailsTable)
                ->join(
                    $masterTable,
                    str($detailsTable)->append('.', str($detailsTable)->before('_')->append('_id')),
                    str($masterTable)->append('.id'))
                ->where('company_id', userCompany()->id)
                ->whereIn($masterTable . '.warehouse_id', $branches)
                ->whereDate($masterTable . '.issued_on', '>=', $period[0])->whereDate($masterTable . '.issued_on', '<=', $period[1])
                ->when(!is_null($status), fn($query) => $query->whereIn($masterTable . '.status', $status)),
        ];
    }
    
    public static function getSalesReturnReportInput($branches, $period)
    {
        return ReturnDetail::query()
            ->whereHas('returnn', function ($q) use ($branches, $period) {
                return $q->whereIn('warehouse_id', $branches)
                    ->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1])
                    ->added()
                    ->withoutGlobalScopes([BranchScope::class]);
            })
            ->join('products', 'return_details.product_id', '=', 'products.id')
            ->join('returns', 'return_details.return_id', '=', 'returns.id')
            ->join('warehouses', 'returns.warehouse_id', '=', 'warehouses.id')
            ->leftJoin('customers', 'returns.customer_id', '=', 'customers.id');
    }

    public static function getExpenseReportInput($branches, $period)
    {
        $source = Expense::whereIn('warehouse_id', $branches)
            ->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1])
            ->withCount('expenseDetails')->having('expense_details_count', '>', 0)
            ->approved()
            ->get();

        $formatedSource = collect();

        foreach ($source as $transaction) {
            $data = [
                'transaction_type' => 'approved_expense',
                'transaction_number' => $transaction->code,
                'branch_name' => $transaction->warehouse->name,
                'supplier_name' => $transaction->supplier->company_name ?? 'No Supplier',
                'transaction_date' => $transaction->issued_on->toFormattedDateString(),
                'tax_amount' => $transaction->vat,
                'purchaser_name' => $transaction->createdBy->name,
                'subtotal_price' => $transaction->subtotal_price,
                'grand_total_price_after_discount' => $transaction->grandTotalPriceAfterDiscount,
                'details' => [],
            ];

            foreach ($transaction->details() as $transactionDetail) {
                $data['details'][] =
                [
                    'transaction_type' => 'approved_expense',
                    'expense_category_name' => $transactionDetail->expenseCategory->name,
                    'unit_price' => $transactionDetail->unit_price,
                    'quantity' => $transactionDetail->quantity,
                ];
            }

            $formatedSource->push($data);
        }

        return $formatedSource;
    }
}
