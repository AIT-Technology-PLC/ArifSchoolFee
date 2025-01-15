<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSmsRequest;

class SmsSettingController extends Controller
{
    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Setting'), 403);

        return view('admin.sms-settings.create');
    }

    public function store(StoreSmsRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Setting'), 403);

        update_static_option('AFROMESSAGE_SINGLE_MESSAGE_URL', $request->AFROMESSAGE_SINGLE_MESSAGE_URL);
        update_static_option('AFROMESSAGE_BULK_MESSAGE_URL', $request->AFROMESSAGE_BULK_MESSAGE_URL);
        update_static_option('AFROMESSAGE_SECURITY_MESSAGE_URL', $request->AFROMESSAGE_SECURITY_MESSAGE_URL);
        update_static_option('AFROMESSAGE_FROM', $request->AFROMESSAGE_FROM);
        update_static_option('AFROMESSAGE_TOKEN', $request->AFROMESSAGE_TOKEN);
        update_static_option('AFROMESSAGE_SENDER', $request->AFROMESSAGE_SENDER);
        update_static_option('AFROMESSAGE_CAMPAIGN_NAME', '"' . $request->AFROMESSAGE_CAMPAIGN_NAME . '"');
        update_static_option('AFROMESSAGE_CALLBACK', $request->AFROMESSAGE_CALLBACK);
        update_static_option('AFROMESSAGE_CREATE_CALLBACK', $request->AFROMESSAGE_CREATE_CALLBACK);
        update_static_option('AFROMESSAGE_STATUS_CALLBACK', $request->AFROMESSAGE_STATUS_CALLBACK);
        update_static_option('SPACES_BEFORE', $request->SPACES_BEFORE);
        update_static_option('SPACES_AFTER', $request->SPACES_AFTER);
        update_static_option('TIME_TO_LIVE', $request->TIME_TO_LIVE);
        update_static_option('CODE_LENGTH', $request->CODE_LENGTH);
        update_static_option('CODE_TYPE', $request->CODE_TYPE);

        return redirect()->route('admin.sms-settings.create')->with('successMessage', 'SMS Setting Data Stored Successfully.');
    }
}
