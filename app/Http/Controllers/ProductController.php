<?php

namespace App\Http\Controllers;

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

    public function __construct(Product $product)
    {
        $this->middleware('isFeatureAccessible:Product Management');

        $this->authorizeResource(Product::class, 'product');

        $this->product = $product;
    }

    public function index()
    {
        $products = $this->product->getAll();

        $totalProductsOfCompany = $this->product->countProductsOfCompany();

        return view('products.index', compact('products', 'totalProductsOfCompany'));
    }

    public function create(ProductCategory $category, Supplier $supplier)
    {
        $categories = $category->getAll();

        $suppliers = $supplier->getSupplierNames();

        $inventoryTypes = $this->getInventoryTypes();

        $unitTypes = $this->getMeasurementUnits();

        return view('products.create', compact('categories', 'suppliers', 'inventoryTypes', 'unitTypes'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->product->firstOrCreate(
            $request->only(['name', 'product_category_id']),
            $request->except(['name', 'product_category_id'])
        );

        return redirect()->route('products.index');
    }

    public function edit(Product $product, ProductCategory $category, Supplier $supplier)
    {
        $categories = $category->getAll();

        $suppliers = $supplier->getSupplierNames();

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

    public function getProductUOM(Product $product)
    {
        return $product->getProductUOM();
    }
}
