<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomerDatatable;
use App\Http\Controllers\Controller;
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

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
