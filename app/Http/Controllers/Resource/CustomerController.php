<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomerDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(CustomerDatatable $datatable)
    {
        $datatable->builder()->setTableId('customers-datatable')->orderBy(1, 'asc');

        $totalCustomers = Customer::count();

        return $datatable->render('customers.index', compact('totalCustomers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        Customer::firstOrCreate(
            $request->safe()->only(['company_name'] + ['company_id' => userCompany()->id]),
            $request->safe()->except(['company_name'] + ['company_id' => userCompany()->id])
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
        $customer->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
