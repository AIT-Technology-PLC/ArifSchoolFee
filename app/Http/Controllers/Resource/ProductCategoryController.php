<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ProductCategoryDatatable;
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

    public function index(ProductCategoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('product-categories-datatable')->orderBy(1, 'asc');

        $totalProductCategories = ProductCategory::count();

        return $datatable->render('categories.index', compact('totalProductCategories'));
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
        $category->update($request->validated());

        return redirect()->route('categories.index');
    }

    public function destroy(ProductCategory $category)
    {
        $category->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
