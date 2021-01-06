<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\StoreSaleableProducts;
use App\Traits\HasOptions;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use HasOptions;

    private $sale;

    public function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }

    public function index()
    {
        $sales = $this->sale->getAll();

        $totalSales = $this->sale->countSalesOfCompany();

        return view('sales.index', compact('sales', 'totalSales'));
    }

    public function create(Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        $shippingLines = $this->getShippingLines();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('sales.create', compact('products', 'customers', 'shippingLines', 'warehouses'));
    }

    public function store(Request $request)
    {
        $saleData = $request->validate([
            'sale' => 'required|array',
            'sale.*.product_id' => 'required|integer',
            'sale.*.warehouse_id' => 'required|integer',
            'sale.*.quantity' => 'required|numeric|min:1',
            'sale.*.unit_price' => 'required|numeric',
            'customer_id' => 'nullable|integer',
            'sold_on' => 'required|date',
            'shipping_line' => 'nullable|string|max:255',
            'status' => 'required|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $saleData['company_id'] = auth()->user()->employee->company_id;
        $saleData['created_by'] = auth()->user()->id;
        $saleData['updated_by'] = auth()->user()->id;

        $basicSaleData = Arr::except($saleData, 'sale');
        $saleDetailsData = $saleData['sale'];

        $isSaleValid = DB::transaction(function () use ($basicSaleData, $saleDetailsData) {
            $sale = $this->sale->create($basicSaleData);
            $sale->saleDetails()->createMany($saleDetailsData);
            $isSaleValid = StoreSaleableProducts::storeSoldProducts($sale);

            if (!$isSaleValid) {
                DB::rollback();
            }

            return $isSaleValid;
        });

        return $isSaleValid ?
        redirect()->route('sales.index') :
        redirect()->back()->withInput($request->all());
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleDetails.product', 'saleDetails.warehouse', 'customer', 'company']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale, Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        $shippingLines = $this->getShippingLines();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('sales.edit', compact('sale', 'products', 'customers', 'shippingLines', 'warehouses'));
    }

    public function update(Request $request, Sale $sale)
    {
        if ($sale->isSaleSubtracted()) {
            return redirect()->route('sales.show', $sale->id);
        }

        $saleData = $request->validate([
            'sale' => 'required|array',
            'sale.*.product_id' => 'required|integer',
            'sale.*.warehouse_id' => 'required|integer',
            'sale.*.quantity' => 'required|numeric|min:1',
            'sale.*.unit_price' => 'required|numeric',
            'customer_id' => 'nullable|integer',
            'sold_on' => 'required|date',
            'shipping_line' => 'nullable|string|max:255',
            'shipped_at' => 'nullable|date',
            'delivered_at' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $saleData['updated_by'] = auth()->user()->id;

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
        //
    }
}
