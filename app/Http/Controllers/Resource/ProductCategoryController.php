<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductCategoryRequest;
use App\Http\Requests\UpdateProductCategoryRequest;
use App\Models\ProductCategory;

class ProductCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');

        $this->authorizeResource(ProductCategory::class, 'category');
    }

    public function index()
    {
        $categories = ProductCategory::with(['createdBy', 'updatedBy'])
            ->withCount('products')
            ->orderBy('name')
            ->get();

        $totalProductCategoriesOfCompany = ProductCategory::count();

        return view('categories.index', compact('categories', 'totalProductCategoriesOfCompany'));
    }

    public function create()
    {
        return view('categories.create');
    }

    public function store(StoreProductCategoryRequest $request)
    {
        ProductCategory::firstOrCreate(
            $request->only(['name'] + ['company_id' => userCompany()->id]),
            $request->except(['name'] + ['company_id' => userCompany()->id]),
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
