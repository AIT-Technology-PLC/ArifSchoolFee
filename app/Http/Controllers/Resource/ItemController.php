<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ItemDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreItemTypeRequest;
use App\Http\Requests\UpdateItemTypeRequest;
use App\Models\Employee;
use App\Models\Item;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Item Type Management');
    }

    public function index(ItemDatatable $datatable)
    {
        $datatable->builder()->setTableId('items-datatable')->orderBy(1, 'asc');

        $totalItemTypes = Item::count();

        return $datatable->render('items.index', compact('totalItemTypes'));
    }

    public function create()
    {
        return view('items.create');
    }

    public function store(StoreItemTypeRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('item') as $item) {
                Item::create($item);
            }
        });

        return redirect()->route('items.index')->with('successMessage', 'New Item Type are added.');
    }

    public function edit(Item $item)
    {
        return view('items.edit', compact('item'));
    }

    public function update(UpdateItemTypeRequest $request, Item $item)
    {
        $item->update($request->validated());

        return redirect()->route('items.index');
    }

    public function destroy(Item $item)
    {
        $item->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
