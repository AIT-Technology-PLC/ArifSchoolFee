<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private $productCategory;

    public function __construct(ProductCategory $productCategory)
    {
        $this->productCategory = $productCategory;
    }

    public function index()
    {
        $categories = $this->productCategory->getAll();

        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['created_by'] = auth()->user()->id;

        $data['updated_by'] = auth()->user()->id;

        $data['company_id'] = auth()->user()->employee->company_id;

        $this->productCategory->create($data);

        return redirect()->route('categories.index');
    }

    public function show(ProductCategory $productCategory)
    {
        return view('categories.show', compact($productCategory));
    }

    public function edit(ProductCategory $productCategory)
    {
        return view('categories.edit', compact($productCategory));
        
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        //
    }

    public function destroy(ProductCategory $productCategory)
    {
        //
    }
}
