<?php

namespace App\Http\Controllers;

use App\Models\ProductCategory;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    private $category;

    public function __construct(ProductCategory $category)
    {
        $this->authorizeResource(ProductCategory::class, 'category');

        $this->category = $category;
    }

    public function index()
    {
        $categories = $this->category->getAll();

        $totalProductCategoriesOfCompany = $this->category->countProductCategoriesOfCompany();

        return view('categories.index', compact('categories', 'totalProductCategoriesOfCompany'));
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
            'properties' => 'nullable|array',
        ]);

        $data['created_by'] = auth()->user()->id;

        $data['updated_by'] = auth()->user()->id;

        $data['company_id'] = auth()->user()->employee->company_id;

        $this->category->create($data);

        return redirect()->route('categories.index');
    }

    public function show(ProductCategory $category)
    {
        return view('categories.show', compact('category'));
    }

    public function edit(ProductCategory $category)
    {
        return view('categories.edit', compact('category'));
    }

    public function update(Request $request, ProductCategory $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'properties' => 'nullable|array',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $category->update($data);

        return redirect()->route('categories.index');
    }

    public function destroy(ProductCategory $category)
    {
        //
    }
}
