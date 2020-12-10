<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Sale;
use App\Traits\HasOptions;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    use HasOptions;

    private $sale;

    function __construct(Sale $sale)
    {
        $this->sale = $sale;
    }
    
    public function index()
    {
        //
    }

    public function create(Product $product, Customer $customer)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $saleStatuses = $this->getSaleStatuses();

        $shippingLines = $this->getShippingLines();

        return view('sales.create', compact('products', 'customers', 'saleStatuses', 'shippingLines'));
    }

    public function store(Request $request)
    {
        //
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
