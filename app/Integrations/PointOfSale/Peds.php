<?php

namespace App\Integrations\PointOfSale;

use App\Interfaces\PointOfSaleInterface;
use App\Models\Sale;
use Illuminate\Support\Facades\Http;

class Peds implements PointOfSaleInterface
{
    private $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Basic UEVEU0FQSTo2MjUxMTM=',
            'Content-Type' => 'application/json',
        ];
    }

    private function getRequestBody()
    {
        $holdSalesItems = $this->sale
            ->saleDetails
            ->map(function ($saleDetail) {
                return [
                    'HoldSalesItemIdentifierId' => (string) $saleDetail->id,
                    'CategoryIdentifierId' => (string) $saleDetail->product->product_category_id,
                    'CategoryName' => $saleDetail->product->productCategory->name,
                    'ItemIdentifierId' => (string) $saleDetail->product_id,
                    'ItemDescription' => $saleDetail->product->name,
                    'ItemCode' => (string) $saleDetail->product->code,
                    'UomIdentifierId' => $saleDetail->product->unit_of_measurement,
                    'UomName' => $saleDetail->product->unit_of_measurement,
                    'Quantity' => $saleDetail->quantity,
                    'SalesUnitPrice' => $saleDetail->unit_price,
                    'TaxType' => '1',
                ];
            })
            ->toArray();

        $holdSales = [
            'HoldSalesIdentifierId' => (string) $this->sale->code,
            'TransactionType' => '0',
            'InvoiceNo' => (string) $this->sale->code,
            'PaymentType' => $this->sale->payment_type == 'Cash Payment' ? '0' : '2',
            'TableNumber' => '',
            'SalesPerson' => $this->sale->createdBy->name,
            'HoldMemo' => '',
            'Date' => $this->sale->created_at,
            'CustomerName' => $this->sale->customer->company_name,
            'CustomerTIN' => $this->sale->customer->tin ?? null,
            'CustomerVAT' => '',
            'CashierUpdated' => $this->sale->updatedBy->name,
            'POSId' => (string) $this->sale->warehouse_id,
            'HoldSalesItems' => $holdSalesItems,
        ];

        return $holdSales;
    }

    public function create()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/API/HoldSalesService/Add', $this->getRequestBody($this->sale));

        return [$response['Success'], $response['Message']];
    }

    public function void()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/api/HoldSalesService/Void', [
                'value' => $this->sale->code,
            ]);

        return [$response['Success'], $response['Message']];
    }

    public function exists()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/api/HoldSalesService/Exists', [
                'value' => $this->sale->code,
            ]);

        return [$response['Success'], $response['Message']];
    }

    public function getStatus()
    {
        $response = Http::withHeaders($this->getHeaders())
            ->post('http://localhost:2010/PEDS/api/HoldSalesService/GetInvoiceStatus', [
                'value' => $this->sale->code,
            ]);

        return $response;
    }

    public function isVoid()
    {
        return $this->getStatus()['Content']['IsVoid'];
    }

    public function isPrinted()
    {
        return $this->getStatus()['Content']['IsPrinted'];
    }
}
