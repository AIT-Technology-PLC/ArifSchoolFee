<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    private $product;

    public function __construct(Product $product)
    {
        $this->authorizeResource(Product::class);
        
        $this->product = $product;
    }

    public function index()
    {
        $products = $this->product->getAll();

        $totalProductsOfCompany = $this->product->countProductsOfCompany();

        return view('products.index', compact('products', 'totalProductsOfCompany'));
    }

    public function create(ProductCategory $productCategory)
    {
        $productCategories = $productCategory->getAll();

        return view('products.create', compact('productCategories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'min_on_hand' => 'required|integer',
            'description' => 'nullable|string',
            'is_expirable' => 'required|integer',
            'properties' => 'nullable|array',
            'product_category_id' => 'nullable|integer',
        ]);

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->employee->company_id;

        $this->product->create($data);

        return redirect()->route('products.index');
    }

    public function show(Product $product)
    {
        //
    }

    public function edit(Product $product, ProductCategory $productCategory)
    {
        $productCategories = $productCategory->getAll();

        return view('products.edit', compact('product', 'productCategories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'unit_of_measurement' => 'required|string|max:255',
            'selling_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'min_on_hand' => 'required|integer',
            'description' => 'nullable|string',
            'is_expirable' => 'required|integer',
            'properties' => 'nullable|array',
            'product_category_id' => 'nullable|integer',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $product->update($data);

        return redirect()->route('products.index');
    }

    public function destroy(Product $product)
    {
        //
    }
}
