<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\SearchFeeDueDatatable;
use App\Models\FeeGroup;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Warehouse;

class SearchFeeDueController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Collect Fee');
    }

    public function index(SearchFeeDueDatatable $datatable)
    {
        abort_if(authUser()->cannot('Search Fee Due'), 403);

        $datatable->builder()->setTableId('search-fee-dues-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        $groups = FeeGroup::orderBy('name')->get(['id', 'name']);

        return $datatable->render('search-fee-dues.index', compact('branches', 'classes', 'sections', 'groups'));
    }
}
