<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProductDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');

        $this->authorizeResource(Product::class, 'product');
    }

    public function index(ProductDatatable $datatable)
    {
        $datatable->builder()->orderBy(1, 'asc');

        $totalProductsOfCompany = Product::count();

        return $datatable->render('products.index', compact('totalProductsOfCompany'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::firstOrCreate(
            $request->only(['name', 'product_category_id'] + ['company_id' => userCompany()->id]),
            $request->except(['name', 'product_category_id'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        return view('products.edit', compact('product', 'categories', 'suppliers'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
