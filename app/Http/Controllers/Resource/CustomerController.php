<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CustomerDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Services\Models\CustomerService;
use Illuminate\Support\Facades\DB;

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
        (new CustomerService)->store($request->validated());

        return redirect()->route('customers.index');
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        DB::transaction(function () use ($customer, $request) {
            $customer->update($request->validated());

            if ($request->hasFile('business_license_attachment')) {
                $customer->update([
                    'business_license_attachment' => $request->business_license_attachment->store('customer_business_licence', 'public'),
                ]);
            }
        });

        return redirect()->route('customers.index');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
