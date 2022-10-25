<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\BrandDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreBrandRequest;
use App\Http\Requests\UpdateBrandRequest;
use App\Models\Brand;
use Illuminate\Support\Facades\DB;

class BrandController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Brand Management');

        $this->authorizeResource(Brand::class);
    }

    public function index(BrandDatatable $datatable)
    {
        $datatable->builder()->setTableId('brands-datatable')->orderBy(1, 'asc');

        $totalBrands = Brand::count();

        return $datatable->render('brands.index', compact('totalBrands'));
    }

    public function create()
    {
        return view('brands.create');
    }

    public function store(StoreBrandRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('brand') as $brand) {
                Brand::create($brand);
            }
        });

        return redirect()->route('brands.index')->with('successMessage', 'New Brand are added.');
    }

    public function edit(Brand $brand)
    {
        return view('brands.edit', compact('brand'));
    }

    public function update(UpdateBrandRequest $request, Brand $brand)
    {
        $brand->update($request->validated());

        return redirect()->route('brands.index');
    }

    public function destroy(Brand $brand)
    {
        $brand->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
