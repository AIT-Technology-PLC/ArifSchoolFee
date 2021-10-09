<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\UpdateReturnRequest;
use App\Models\Customer;
use App\Models\Returnn;
use App\Notifications\ReturnPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->authorizeResource(Returnn::class, 'return');
    }

    public function index()
    {
        $returns = (new Returnn)->getAll()->load(['returnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'customer']);

        $totalReturns = Returnn::count();

        $totalNotApproved = Returnn::whereNull('approved_by')->count();

        $totalNotAdded = Returnn::whereNotNull('approved_by')->whereNull('added_by')->count();

        $totalAdded = Returnn::whereNotNull('added_by')->count();

        return view('returns.index', compact('returns', 'totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = user()->getAllowedWarehouses('add');

        $currentReturnCode = Returnn::max('code') + 1;

        return view('returns.create', compact('customers', 'warehouses', 'currentReturnCode'));
    }

    public function store(StoreReturnRequest $request)
    {
        $return = DB::transaction(function () use ($request) {
            $return = Returnn::create($request->except('return'));

            $return->returnDetails()->createMany($request->return);

            Notification::send(notifiables('Approve Return'), new ReturnPrepared($return));

            return $return;
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function show(Returnn $return)
    {
        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'customer']);

        return view('returns.show', compact('return'));
    }

    public function edit(Returnn $return)
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = user()->getAllowedWarehouses('add');

        $return->load(['returnDetails.product', 'returnDetails.warehouse']);

        return view('returns.edit', compact('return', 'customers', 'warehouses'));
    }

    public function update(UpdateReturnRequest $request, Returnn $return)
    {
        if ($return->isApproved()) {
            return redirect()->route('returns.show', $return->id);
        }

        DB::transaction(function () use ($request, $return) {
            $return->update($request->except('return'));

            for ($i = 0; $i < count($request->return); $i++) {
                $return->returnDetails[$i]->update($request->return[$i]);
            }
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function destroy(Returnn $return)
    {
        if ($return->isAdded()) {
            abort(403);
        }

        if ($return->isApproved() && !auth()->user()->can('Delete Approved Return')) {
            abort(403);
        }

        $return->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
