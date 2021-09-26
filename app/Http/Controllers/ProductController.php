<?php

namespace App\Http\Controllers;

use App\DataTables\ProductDatatable;
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

    public function index(ProductDatatable $datatable)
    {
        $totalProductsOfCompany = $this->product->countProductsOfCompany();

        return $datatable->render('products.index', compact('totalProductsOfCompany'));
    }

    public function create(ProductCategory $category)
    {
        $categories = $category->getAll();

        $suppliers = Supplier::orderBy('company_name')->get(['id', 'company_name']);

        $inventoryTypes = $this->getInventoryTypes();

        $unitTypes = $this->getMeasurementUnits();

        return view('products.create', compact('categories', 'suppliers', 'inventoryTypes', 'unitTypes'));
    }

    public function store(StoreProductRequest $request)
    {
        $this->product->firstOrCreate(
            $request->only(['name', 'product_category_id'] + ['company_id' => userCompany()->id]),
            $request->except(['name', 'product_category_id'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('products.index');
    }

    public function edit(Product $product, ProductCategory $category)
    {
        $categories = $category->getAll();

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

    public function getProductUOM(Product $product)
    {
        return $product->getProductUOM();
    }
}
