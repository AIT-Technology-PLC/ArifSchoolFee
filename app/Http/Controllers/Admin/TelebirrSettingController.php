<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreTelebirrRequest;

class TelebirrSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Payment'), 403);

        return view('admin.telebirr-settings.create');
    }

    public function store(StoreTelebirrRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Payment'), 403);

        update_static_option('TELEBIRR_MERCHANT_APP_ID', $request->TELEBIRR_MERCHANT_APP_ID);
        update_static_option('TELEBIRR_FABRIC_APP_ID', $request->TELEBIRR_FABRIC_APP_ID);
        update_static_option('TELEBIRR_APP_SECRET', $request->TELEBIRR_APP_SECRET);
        update_static_option('TELEBIRR_MERCHANT_CODE', $request->TELEBIRR_MERCHANT_CODE);
        update_static_option('TELEBIRR_BASE_URL', $request->TELEBIRR_BASE_URL);
        update_static_option('TELEBIRR_PRIVATE_KEY', $request->TELEBIRR_PRIVATE_KEY);

        return redirect()->route('admin.telebirr-settings.create')->with('successMessage', 'Telebirr Setting Data Stored Successfully.');
    }
}