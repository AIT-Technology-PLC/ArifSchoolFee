<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\StoreSaleableProducts;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use PrependCompanyId;

    private $sale;

    public function __construct(Sale $sale)
    {
        $this->authorizeResource(Sale::class, 'sale');

        $this->sale = $sale;
    }

    public function index()
    {
        $sales = $this->sale->getAll()->load(['createdBy', 'updatedBy', 'company', 'saleDetails']);

        $totalSales = $this->sale->countSalesOfCompany();

        return view('sales.index', compact('sales', 'totalSales'));
    }

    public function create(Product $product, Customer $customer)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        $currentReceiptNo = (Sale::select('receipt_no')->companySales()->latest()->first()->receipt_no) ?? 0;

        return view('sales.create', compact('products', 'customers', 'currentReceiptNo'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $sale = $this->sale->create($request->except('sale'));

            $sale->saleDetails()->createMany($request->sale);

            if ($sale->isSaleManual()) {
                $isSaleValid = StoreSaleableProducts::areProductsSaleable($sale->saleDetails) &&
                StoreSaleableProducts::areProductsPricesValid($sale->saleDetails);
            }

            if (!$sale->isSaleManual()) {
                $isSaleValid = StoreSaleableProducts::storeSoldProducts($sale);
            }

            if (!$isSaleValid) {
                DB::rollback();
            }

            return $isSaleValid ? $sale : false;
        });

        return $sale ?
        redirect()->route('sales.show', $sale->id) :
        redirect()->back()->withInput($request->all());
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleDetails.product', 'gdns', 'customer', 'company']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale, Product $product, Customer $customer)
    {
        $sale->load('saleDetails.product');

        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        return view('sales.edit', compact('sale', 'products', 'customers'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->isSaleSubtracted()) {
            return redirect()->route('sales.show', $sale->id);
        }

        $request['receipt_no'] = $this->prependCompanyId($request->receipt_no);

        $saleData = $request->validate([
            'receipt_no' => 'required|string|unique:sales,receipt_no,' . $sale->id,
            'sale' => 'required|array',
            'sale.*.product_id' => 'required|integer',
            'sale.*.quantity' => 'required|numeric|min:1',
            'sale.*.unit_price' => 'required|numeric',
            'customer_id' => 'nullable|integer',
            'sold_on' => 'required|date',
            'payment_type' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $saleData['updated_by'] = auth()->id();

        $basicSaleData = Arr::except($saleData, 'sale');
        $saleDetailsData = $saleData['sale'];

        DB::transaction(function () use ($basicSaleData, $saleDetailsData, $sale) {
            $sale->update($basicSaleData);

            for ($i = 0; $i < count($saleDetailsData); $i++) {
                $sale->saleDetails[$i]->update($saleDetailsData[$i]);
            }
        });

        return redirect()->route('sales.show', $sale->id);

    }

    public function destroy(Sale $sale)
    {
        $sale->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
