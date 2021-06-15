<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    private $category;

    public function __construct(ProductCategory $category)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Product Management');

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

    public function store(StoreProductCategoryRequest $request)
    {
        $this->category->firstOrCreate(
            $request->only(['name', 'company_id']),
            $request->except(['name', 'company_id']),
        );

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

    public function update(UpdateProductCategoryRequest $request, ProductCategory $category)
    {
        $category->update($request->all());

        return redirect()->route('categories.index');
    }

    public function destroy(ProductCategory $category)
    {
        $category->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
