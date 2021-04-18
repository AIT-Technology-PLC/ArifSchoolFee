<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $data['company_id'] = auth()->user()->employee->company_id;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $this->customer->firstOrCreate(
            Arr::only($data, ['company_name', 'company_id']),
            Arr::except($data, ['company_name', 'company_id'])
        );

        return redirect()->route('customers.index');
    }

    public function show(Customer $customer)
    {
        //
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $customer->update($data);

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
