<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTelebirrRequest;

class TelebirrSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.telebirr-settings.create');
    }

    public function store(StoreTelebirrRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        update_static_option('TELEBIRR_CHECKOUT_URL', $request->TELEBIRR_CHECKOUT_URL);
        update_static_option('TELEBIRR_API_KEY', $request->TELEBIRR_API_KEY);
        update_static_option('TELEBIRR_SECRET_KEY', $request->TELEBIRR_SECRET_KEY);
        update_static_option('TELEBIRR_CLIENT_ID', $request->TELEBIRR_CLIENT_ID);

        return redirect()->route('admin.telebirr-settings.create')->with('successMessage', 'Telebirr Setting Data Stored Successfully.');
    }
}