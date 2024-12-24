<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreStripeRequest;

class StripeSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.stripe-settings.create');
    }

    public function store(StoreStripeRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        update_static_option('STRIPE_SECRET_KEY', $request->STRIPE_SECRET_KEY);
        update_static_option('STRIPE_PUBLISHABLE_KEY', $request->STRIPE_PUBLISHABLE_KEY);
        update_static_option('STRIPE_PAYMENT_MODE', $request->STRIPE_PAYMENT_MODE);

        return redirect()->route('admin.stripe-settings.create')->with('successMessage', 'Stripe Setting Data Stored Successfully.');
    }
}
