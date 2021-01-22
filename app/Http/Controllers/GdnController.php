<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class GdnController extends Controller
{
    public function index()
    {
        //
    }

    public function create(Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getAll();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('gdns.create', compact('products', 'customers', 'sales', 'warehouses'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Gdn $gdn)
    {
        //
    }

    public function edit(Gdn $gdn)
    {
        //
    }

    public function update(Request $request, Gdn $gdn)
    {
        //
    }

    public function destroy(Gdn $gdn)
    {
        //
    }
}
