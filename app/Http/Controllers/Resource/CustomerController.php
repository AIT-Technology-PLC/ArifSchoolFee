<?php

namespace App\Http\Controllers\Resource;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Http\Controllers\Controller;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index()
    {
        $customers = Customer::with(['createdBy', 'updatedBy'])->orderBy('company_name')->get();

        $totalCustomers = Customer::count();

        return view('customers.index', compact('customers', 'totalCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::firstOrCreate(
            $request->only(['company_name'] + ['company_id' => userCompany()->id]),
            $request->except(['company_name'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->validated());

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
