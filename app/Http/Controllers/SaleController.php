<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\PrependCompanyId;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    use PrependCompanyId;

    private $sale;

    public function __construct(Sale $sale)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Sales Invoice');

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

            return $sale;
        });

        return redirect()->route('sales.show', $sale->id);
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

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        DB::transaction(function () use ($request, $sale) {
            $sale->update($request->except('sale'));

            for ($i = 0; $i < count($request->sale); $i++) {
                $sale->saleDetails[$i]->update($request->sale[$i]);
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
