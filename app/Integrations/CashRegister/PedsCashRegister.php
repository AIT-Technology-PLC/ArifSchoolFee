<?php
namespace App\Integrations\CashRegister;

use App\Interfaces\CashRegisterInterface;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;

class PedsCashRegister implements CashRegisterInterface
{
    private function getHeaders()
    {
        return [
            'Authorization' => 'Basic UEVEU0FQSTo2MjUxMTM=',
            'Content-Type' => 'application/json',
        ];
    }

    private function getRequestBody($sale)
    {
        $holdSalesItems = $sale
            ->saleDetails
            ->map(function ($saleDetail) {
                return [
                    'HoldSalesItemIdentifierId' => $saleDetail->id,
                    'CategoryIdentifierId' => $saleDetail->product->product_category_id,
                    'CategoryName' => $saleDetail->product->productCategory->name,
                    'ItemIdentifierId' => $saleDetail->product_id,
                    'ItemDescription' => $saleDetail->product->name,
                    'ItemCode' => $saleDetail->product->code,
                    'UomIdentifierId' => $saleDetail->product->unit_of_measurement,
                    'UomName' => $saleDetail->product->unit_of_measurement,
                    'Quantity' => $saleDetail->quantity,
                    'SalesUnitPrice' => $saleDetail->unit_price,
                    // 'TaxType' => '1',
                ];
            })
            ->toArray();

        $holdSales = [
            'HoldSalesIdentifierId' => $sale->id,
            'TransactionType' => '0',
            'InvoiceNo' => $sale->code,
            'PaymentType' => $sale->payment_type == 'Cash Payment' ? '0' : '2',
            // 'TableNumber' => '',
            'SalesPerson' => $sale->createdBy->name,
            // 'HoldMemo' => '',
            'Date' => $sale->created_at,
            'CustomerName' => $sale->customer->company_name,
            'CustomerTIN' => $sale->customer?->tin,
            'CustomerVAT' => '',
            'CashierUpdated' => $sale->updatedBy->name,
            'POSId' => $sale->warehouse_id,
            'HoldSalesItems' => $holdSalesItems,
        ];

        return $holdSales;
    }

    public function create(Sale $sale)
    {
        return Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/API/HoldSalesService/Add', $this->getRequestBody($sale));
    }

    public function cancel($saleCode)
    {
        return Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/api/HoldSalesService/Void', [
                'value' => $saleCode,
            ]);
    }

    public function getStatus($saleCode)
    {
        return Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/api/HoldSalesService/Void', [
                'value' => $saleCode,
            ]);
    }
}
