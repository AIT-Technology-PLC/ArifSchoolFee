<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PaymentGatewayDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentGatewayRequest;
use App\Http\Requests\Admin\UpdatePaymentGatewayRequest;
use App\Models\Company;
use App\Models\PaymentGateway;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class PaymentGatewayController extends Controller
{
    public function index(PaymentGatewayDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $datatable->builder()->setTableId('payment-methods-datatable')->orderBy(1, 'asc');

        return $datatable->render('admin.payment-gateways.index');
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $schools = Company::enabled()->orderBy('name')->get(['id', 'name']);

        $methods = PaymentMethod::enabled()->orderBy('name')->get(['id', 'name']);

        return view('admin.payment-gateways.create', compact('schools','methods'));
    }

    public function store(StorePaymentGatewayRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        PaymentGateway::create($request->validated());

        return redirect()->route('admin.payment-gateways.index')->with('successMessage', 'Payment gateway created successfully');
    }

    public function edit(PaymentGateway $paymentGateway)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $schools = Company::enabled()->enabled()->orderBy('name')->get(['id', 'name']);

        $methods = PaymentMethod::enabled()->orderBy('name')->get(['id', 'name']);

        return view('admin.payment-gateways.edit', compact('paymentGateway', 'schools','methods'));
    }

    public function update(UpdatePaymentGatewayRequest $request, PaymentGateway $paymentGateway)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $paymentGateway->update($request->validated());

        return redirect()->route('admin.payment-gateways.index')->with('successMessage', 'Updated Successfully.');
    }
}
