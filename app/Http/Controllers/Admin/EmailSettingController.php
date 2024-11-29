<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmailRequest;

class EmailSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.email-settings.create');
    }

    public function store(StoreEmailRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        update_static_option('MAIL_MAILER', $request->MAIL_MAILER);
        update_static_option('MAIL_HOST', $request->MAIL_HOST);
        update_static_option('MAIL_PORT', $request->MAIL_PORT);
        update_static_option('MAIL_USERNAME', $request->MAIL_USERNAME);
        update_static_option('MAIL_PASSWORD', $request->MAIL_PASSWORD);
        update_static_option('MAIL_ENCRYPTION', $request->MAIL_ENCRYPTION);
        update_static_option('MAIL_FROM_ADDRESS', '"' . $request->MAIL_FROM_ADDRESS . '"');
        update_static_option('MAIL_FROM_NAME', '"' . $request->MAIL_FROM_NAME . '"');

        return redirect()->route('admin.email-settings.create')->with('successMessage', 'Email Setting Data Stored Successfully.');
    }
}
