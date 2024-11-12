<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSmsRequest;
use App\Models\SmsSetting;

class SmsSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $Sms = SmsSetting::first();

        return view('admin.sms-settings.create', compact('Sms'));
    }

    public function store(StoreSmsRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        SmsSetting::updateOrCreate(
            ['token' => $request->token],
            $request->validated()
        );

        return redirect()->route('admin.sms-settings.create')->with('successMessage', 'Sms Setting Updated Successfully.');
    }
}
