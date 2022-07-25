<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\EarningCategoryDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEarningCategoryRequest;
use App\Http\Requests\UpdateEarningCategoryRequest;
use App\Models\EarningCategory;
use Illuminate\Support\Facades\DB;

class EarningCategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Earning Management');

        $this->authorizeResource(EarningCategory::class);
    }

    public function index(EarningCategoryDatatable $datatable)
    {
        $datatable->builder()->setTableId('earning-categories-datatable')->orderBy(1, 'asc');

        $totalEarningCategories = EarningCategory::count();

        return $datatable->render('earning-categories.index', compact('totalEarningCategories'));
    }

    public function create()
    {
        return view('earning-categories.create');
    }

    public function store(StoreEarningCategoryRequest $request)
    {
        $earningCategories = collect($request->validated('earningCategory'));

        DB::transaction(function () use ($earningCategories) {
            foreach ($earningCategories as $earningCategory) {
                EarningCategory::create($earningCategory);
            }
        });

        return redirect()->route('earning-categories.index');
    }

    public function edit(EarningCategory $earningCategory)
    {
        return view('earning-categories.edit', compact('earningCategory'));
    }

    public function update(UpdateEarningCategoryRequest $request, EarningCategory $earningCategory)
    {
        $earningCategory->update($request->validated());

        return redirect()->route('earning-categories.index');
    }

    public function destroy(EarningCategory $earningCategory)
    {
        $earningCategory->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
