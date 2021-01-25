<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\Supplier;
use App\Traits\HasOptions;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    use HasOptions;

    private $product;

    public function __construct(Product $product)
    {
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'selling_price' => 'nullable|numeric',
            'purchase_price' => 'nullable|numeric',
            'min_on_hand' => 'required|numeric',
            'description' => 'nullable|string',
            'properties' => 'nullable|array',
            'product_category_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->employee->company_id;

        $this->product->firstOrCreate($data);

        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product, ProductCategory $category, Supplier $supplier)
    {
        $categories = $category->getAll();

        $suppliers = $supplier->getSupplierNames();

        $inventoryTypes = $this->getInventoryTypes();

        $unitTypes = $this->getMeasurementUnits();

        return view('products.edit', compact('product', 'categories', 'suppliers', 'inventoryTypes', 'unitTypes'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'code' => 'nullable|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'selling_price' => 'nullable|numeric',
            'purchase_price' => 'nullable|numeric',
            'min_on_hand' => 'required|numeric',
            'description' => 'nullable|string',
            'properties' => 'nullable|array',
            'product_category_id' => 'required|integer',
            'supplier_id' => 'nullable|integer',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $product->update($data);

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
