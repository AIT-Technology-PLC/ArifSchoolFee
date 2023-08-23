<?php

namespace App\Integrations\PointOfSale;

use App\Interfaces\PointOfSaleInterface;
use App\Models\Sale;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class Peds implements PointOfSaleInterface
{
    private $sale;

    private $hostAddress;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;

        $this->hostAddress = str($this->sale->warehouse->host_address)->append('/')->toString();
    }

    private function getHeaders()
    {
        return [
            'Authorization' => 'Basic UEVEU0FQSTo2MjUxMTM=',
            'Content-Type' => 'application/json',
            'Bypass-Tunnel-Reminder' => $this->sale->warehouse->host_address,
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
                    'ItemCode' => (string) $saleDetail->product->code ?? $saleDetail->product->name,
                    'UomIdentifierId' => $saleDetail->product->unit_of_measurement,
                    'UomName' => $saleDetail->product->unit_of_measurement,
                    'Quantity' => $saleDetail->quantity,
                    'SalesUnitPrice' => $saleDetail->unit_price,
                    'TaxType' => $saleDetail->product->tax->type == 'VAT' ? '1' : '4',
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
            'CustomerName' => $this->sale->customer->company_name ?? 'walking customer',
            'CustomerTIN' => $this->sale->customer->tin ?? '',
            'CustomerVAT' => '',
            'CashierUpdated' => $this->sale->updatedBy->name,
            'POSId' => (string) $this->sale->warehouse_id,
            'HoldSalesItems' => $holdSalesItems,
        ];

        return $holdSales;
    }

    public function create()
    {
        try {
            $response = Http::retry(2, throw :false)
                ->connectTimeout(5)
                ->timeout(5)
                ->withHeaders($this->getHeaders())
                ->post(str($this->hostAddress)->append('Add')->toString(), $this->getRequestBody($this->sale));

            if (!isset($response['Success']) || $response->failed()) {
                $data = [false, 'Can not connect to the cashier\'s computer.'];
            }

            if (isset($response['Success']) && !$response['Success'] && str($response['Message'])->contains(['invoice', 'exist', 'invoices', 'exists'])) {
                $data = [true, ''];
            }

            if (isset($response['Success']) && $response['Success']) {
                $data = [$response['Success'], $response['Message']];
            }

            if (!isset($data)) {
                $data = [false, $response['Message'] ?? 'Something went wrong. Please try again.'];
            }
        } catch (ConnectionException $ex) {
            $data = [false, 'NETWORK UNSTABLE: Lost connection to the cashier\'s computer. Try approving again!'];
        }

        return $data;
    }

    public function getFsNumber()
    {
        try {
            $response = Http::retry(3, throw :false)
                ->connectTimeout(5)
                ->timeout(5)
                ->withHeaders($this->getHeaders())
                ->post(str($this->hostAddress)->append('GetPaidStatus')->toString(), $this->sale->code);

            if (!isset($response['Success']) || $response->failed()) {
                $data = [false, 'Can not connect to the cashier\'s computer.'];
            }

            if (isset($response['Success']) && $response['Success']) {
                $data = [$response['Success'], $response['Content'][0]['FsInvoiceNo'] ?? ''];
            }
        } catch (ConnectionException $ex) {
            $data = [false, 'NETWORK UNSTABLE: Lost connection to the cashier\'s computer. Try approving again!'];
        }

        return $data;
    }

    public function isVoid()
    {
        try {
            $response = Http::retry(3, throw :false)
                ->connectTimeout(5)
                ->timeout(5)
                ->withHeaders($this->getHeaders())
                ->post(str($this->hostAddress)->append('GetInvoiceStatus')->toString(), $this->sale->code);

            $data = false;

            if (isset($response['Success']) && $response['Success']) {
                $data = $response['Content']['Status'] == 'FullyVoid';
            }
        } catch (ConnectionException $ex) {
            $data = false;
        }

        return $data;
    }
}
