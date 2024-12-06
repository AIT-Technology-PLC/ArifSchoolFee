<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePaypalRequest;

class PaypalSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.paypal-settings.create');
    }

    public function store(StorePaypalRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        update_static_option('AFROMESSAGE_SINGLE_MESSAGE_URL', $request->AFROMESSAGE_SINGLE_MESSAGE_URL);
        update_static_option('AFROMESSAGE_BULK_MESSAGE_URL', $request->AFROMESSAGE_BULK_MESSAGE_URL);
        update_static_option('AFROMESSAGE_SECURITY_MESSAGE_URL', $request->AFROMESSAGE_SECURITY_MESSAGE_URL);

        return redirect()->route('admin.paypal-settings.create')->with('successMessage', 'PayPal Setting Data Stored Successfully.');
    }
}
