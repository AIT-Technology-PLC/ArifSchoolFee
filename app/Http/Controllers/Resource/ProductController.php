<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProductDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Traits\HasOptions;

class ProductController extends Controller
{
    use HasOptions;

    private $product;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');

        $this->authorizeResource(Product::class, 'product');
    }

    public function index(ProductDatatable $datatable)
    {
        $totalProductsOfCompany = Product::count();

        return $datatable->render('products.index', compact('totalProductsOfCompany'));
    }

    public function create()
    {
        $categories = ProductCategory::orderBy('name')->get(['id', 'name']);

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $inventoryTypes = $this->getInventoryTypes();

        $unitTypes = $this->getMeasurementUnits();

        return view('products.create', compact('categories', 'suppliers', 'inventoryTypes', 'unitTypes'));
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

        $inventoryTypes = $this->getInventoryTypes();

        $unitTypes = $this->getMeasurementUnits();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'inventoryTypes', 'unitTypes'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $product->update($request->all());

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        $product->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
