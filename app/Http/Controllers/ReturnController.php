<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReturnRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Returnn;
use App\Models\Warehouse;
use App\Notifications\ReturnPrepared;
use App\Traits\AddInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    use NotifiableUsers, AddInventory;

    public function __construct()
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Return Management');

        $this->authorizeResource(Returnn::class, 'return');

        $this->permission = 'Make Return';
    }
    public function index()
    {
        $returns = Returnn::companyReturn()
            ->with(['returnDetails', 'createdBy', 'approvedBy', 'returnedBy', 'customer'])
            ->latest()->get();

        $totalReturns = $returns->count();

        $totalNotApproved = $returns->whereNull('approved_by')->count();

        $totalNotAdded = $returns->whereNotNull('approved_by')->whereNull('returned_by')->count();

        $totalAdded = $returns->whereNotNull('returned_by')->count();

        return view('returns.index', compact('returns', 'totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create(Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentReturnCode = (Returnn::select('code')->companyReturn()->latest()->first()->code) ?? 0;

        return view('returns.create', compact('products', 'customers', 'warehouses', 'currentReturnCode'));
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

    public function edit(Returnn $return, Product $product, Customer $customer, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $return->load(['returnDetails.product', 'returnDetails.warehouse']);

        return view('returns.edit', compact('return', 'products', 'customers', 'warehouses'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
