<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\PaymentMethodDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaymentMethodRequest;
use App\Http\Requests\Admin\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;

class PaymentMethodController extends Controller
{
    public function index(PaymentMethodDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $datatable->builder()->setTableId('payment-methods-datatable')->orderBy(1, 'asc');

        $totalMethods = PaymentMethod::count();

        return $datatable->render('admin.payment-methods.index', compact('totalMethods'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.payment-methods.create');
    }

    public function store(StorePaymentMethodRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        PaymentMethod::create($request->validated());

        return redirect()->route('admin.payment-methods.index')->with('successMessage', 'Payment method created successfully');
    }

    public function edit(PaymentMethod $paymentMethod)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.payment-methods.edit', compact('paymentMethod'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $paymentMethod)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $paymentMethod->update($request->validated());

        return redirect()->route('admin.payment-methods.index')->with('successMessage', 'Updated Successfully.');
    }
}
