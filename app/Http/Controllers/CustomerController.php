<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Support\Arr;

class CustomerController extends Controller
{
    private $customer;

    public function __construct(Customer $customer)
    {
        $this->authorizeResource(Customer::class, 'customer');

        $this->customer = $customer;
    }

    public function index()
    {
        $customers = $this->customer->getAll();

        $totalCustomers = $this->customer->countCustomersOfCompany();

        return view('customers.index', compact('customers', 'totalCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $this->customer->firstOrCreate(
            Arr::only($request->all(), ['company_name', 'company_id']),
            Arr::except($request->all(), ['company_name', 'company_id'])
        );

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $customer->update($request->all());

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
