<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Services\SaleableProductChecker;
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
        //
    }

    public function create(Product $product, Customer $customer)
    {
        $products = $product->getSaleableProducts();

        $customers = $customer->getCustomerNames();

        $saleStatuses = $this->getSaleStatuses();

        $shippingLines = $this->getShippingLines();

        return view('sales.create', compact('products', 'customers', 'saleStatuses', 'shippingLines'));
    }

    public function store(Request $request)
    {
        $saleData = $request->validate([
            'sale' => 'required|array',
            'sale.*.product_id' => 'required|integer',
            'sale.*.quantity' => 'required|numeric',
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

        $areProductsSaleable = SaleableProductChecker::areProductsSaleable($saleDetailsData);

        DB::transaction(function () use ($basicSaleData, $saleDetailsData) {
            $sale = $this->sale->create($basicSaleData);
            $sale->saleDetails()->createMany($saleDetailsData);
        });

        return redirect()->route('sales.index');
    }

    public function show(Sale $sale)
    {
        //
    }

    public function edit(Sale $sale)
    {
        //
    }

    public function update(Request $request, Sale $sale)
    {
        //
    }

    public function destroy(Sale $sale)
    {
        //
    }
}
