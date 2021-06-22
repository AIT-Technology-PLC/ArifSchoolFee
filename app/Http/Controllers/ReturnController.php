<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Returnn;
use App\Models\Warehouse;
use App\Traits\AddInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Http\Request;

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

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
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
