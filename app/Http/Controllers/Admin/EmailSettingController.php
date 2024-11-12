<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreEmailRequest;
use App\Models\EmailSetting;

class EmailSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $Email = EmailSetting::first();

        return view('admin.email-settings.create', compact('Email'));
    }

    public function store(StoreEmailRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        EmailSetting::updateOrCreate(
            ['from_mail' => $request->from_mail],
            $request->validated()
        );

        return redirect()->route('admin.email-settings.create')->with('successMessage', 'Email Setting Created Successfully.');
    }
}
