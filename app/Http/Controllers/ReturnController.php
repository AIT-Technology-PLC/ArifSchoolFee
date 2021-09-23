<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\UpdateReturnRequest;
use App\Models\Customer;
use App\Models\Returnn;
use App\Models\Warehouse;
use App\Notifications\ReturnPrepared;
use App\Traits\AddInventory;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    use NotifiableUsers, AddInventory, ApproveInventory;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->authorizeResource(Returnn::class, 'return');

        $this->permission = 'Make Return';
    }
    public function index()
    {
        $returns = (new Returnn())->getAll()->load(['returnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'customer', 'company']);

        $totalReturns = $returns->count();

        $totalNotApproved = $returns->whereNull('approved_by')->count();

        $totalNotAdded = $returns->whereNotNull('approved_by')->whereNull('added_by')->count();

        $totalAdded = $returns->whereNotNull('added_by')->count();

        return view('returns.index', compact('returns', 'totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create(Customer $customer)
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->addWarehouses())->get(['id', 'name']);

        $currentReturnCode = Returnn::byBranch()->max('code') + 1;

        return view('returns.create', compact('customers', 'warehouses', 'currentReturnCode'));
    }

    public function store(StoreReturnRequest $request)
    {
        $return = DB::transaction(function () use ($request) {
            $return = Returnn::create($request->except('return'));

            $return->returnDetails()->createMany($request->return);

            Notification::send($this->notifiableUsers('Approve Return'), new ReturnPrepared($return));

            return $return;
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function show(Returnn $return)
    {
        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'customer', 'company']);

        return view('returns.show', compact('return'));
    }

    public function edit(Returnn $return, Customer $customer)
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->addWarehouses())->get(['id', 'name']);

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

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function printed(Returnn $return)
    {
        $this->authorize('view', $return);

        $return->load(['returnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('returns.print', compact('return'));
    }
}
