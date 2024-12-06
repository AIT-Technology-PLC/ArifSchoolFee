<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreArifpayRequest;

class ArifpaySettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.arifpay-settings.create');
    }

    public function store(StoreArifpayRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        update_static_option('ARIFPAY_CHECKOUT_URL', $request->ARIFPAY_CHECKOUT_URL);
        update_static_option('ARIFPAY_SECRET_KEY', $request->ARIFPAY_SECRET_KEY);
        update_static_option('ARIFPAY_API_KEY', $request->ARIFPAY_API_KEY);

        return redirect()->route('admin.arifpay-settings.create')->with('successMessage', 'Arifpay Setting Data Stored Successfully.');
    }
}
