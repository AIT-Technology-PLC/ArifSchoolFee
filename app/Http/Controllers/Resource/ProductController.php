<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProductDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Brand;
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
        $datatable->builder()->setTableId('products-datatable')->orderBy(1, 'asc');

        $totalProducts = Product::count();

        return $datatable->render('products.index', compact('totalProducts'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $brands = Brand::orderBy('name')->get(['id', 'name']);

        return view('products.create', compact('categories', 'suppliers', 'brands'));
    }

    public function store(StoreProductRequest $request)
    {
        Product::firstOrCreate(
            $request->safe()->only(['name', 'code', 'product_category_id'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['name', 'code', 'product_category_id'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('products.index');
    }

    public function edit(Product $product)
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $brands = Brand::orderBy('name')->get(['id', 'name']);

        return view('products.edit', compact('product', 'categories', 'suppliers', 'brands'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->validated());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
