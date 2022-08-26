<?php

namespace App\Reports;

use App\Models\Gdn;
use App\Models\Sale;

class ReportSource
{
    public static function getSalesReportInput($branch = null, $period = null)
    {
        $source = match(userCompany()->sales_report_source) {
            'All Delivery Orders' => Gdn::when(!is_null($branch), fn($q) => $q->where('warehouse_id', $branch))
                ->when(!is_null($period), fn($query) => $query->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1]))
                ->when(is_null($period), fn($query) => $query->whereDate('issued_on', '>=', today()->subDays(6))->whereDate('issued_on', '<=', today()))
                ->withCount('gdnDetails')->having('gdn_details_count', '>', 0)
                ->get(),
            'Approved Delivery Orders' => Gdn::when(!is_null($branch), fn($q) => $q->where('warehouse_id', $branch))
                ->when(!is_null($period), fn($query) => $query->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1]))
                ->when(is_null($period), fn($query) => $query->whereDate('issued_on', '>=', today()->subDays(6))->whereDate('issued_on', '<=', today()))
                ->withCount('gdnDetails')->having('gdn_details_count', '>', 0)
                ->approved()
                ->get(),
            'Subtracted Delivery Orders' => Gdn::when(!is_null($branch), fn($q) => $q->where('warehouse_id', $branch))
                ->when(!is_null($period), fn($query) => $query->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1]))
                ->withCount('gdnDetails')->having('gdn_details_count', '>', 0)
                ->subtracted()
                ->get(),
            'All Invoices' => Sale::when(!is_null($branch), fn($q) => $q->where('warehouse_id', $branch))
                ->when(!is_null($period), fn($query) => $query->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1]))
                ->withCount('saleDetails')->having('sale_details_count', '>', 0)
                ->get(),
            'Approved Invoices' => Sale::when(!is_null($branch), fn($q) => $q->where('warehouse_id', $branch))
                ->when(!is_null($period), fn($query) => $query->whereDate('issued_on', '>=', $period[0])->whereDate('issued_on', '<=', $period[1]))
                ->withCount('saleDetails')->having('sale_details_count', '>', 0)
                ->approved()
                ->get(),
        };

        $formatedSource = collect();

        foreach ($source as $transaction) {
            $data = [
                'transaction_type' => 'completed_sales',
                'transaction_number' => $transaction->code,
                'branch_name' => $transaction->warehouse->name,
                'customer_name' => $transaction->customer->company_name ?? 'No Customer',
                'customer_location' => $transaction->customer->address ?? null,
                'transaction_date' => $transaction->issued_on->toFormattedDateString(),
                'payment_method' => $transaction->payment_type,
                'sales_rep_name' => $transaction->createdBy->name,
                'subtotal_price' => $transaction->subtotal_price,
                'tax_amount' => $transaction->vat,
                'grand_total_price_after_discount' => $transaction->grandTotalPriceAfterDiscount,
                'cash_amount' => $transaction->paymentInCash,
                'credit_amount' => $transaction->paymentInCredit,
                'details' => [],
            ];

            foreach ($transaction->details() as $transactionDetail) {
                $data['details'][] =
                    [
                    'transaction_type' => 'completed_sales',
                    'transaction_number' => $transaction->code,
                    'product_category_name' => $transactionDetail->product->productCategory->name,
                    'product_name' => $transactionDetail->product->name,
                    'unit_of_measurement' => $transactionDetail->product->unit_of_measurement,
                    'unit_price' => $transactionDetail->unit_price,
                    'quantity' => $transactionDetail->quantity,
                ];
            }

            $formatedSource->push($data);
        }

        return $formatedSource;
    }
}
