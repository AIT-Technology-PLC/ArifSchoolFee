<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WebNotificationController extends Controller
{
    public function update(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        authUser()->updatePushSubscription(
            $request->endpoint,
            $request->publicKey,
            $request->authToken,
            $request->contentEncoding
        );

        return back()->with('success', 'Notification send successfully.');
    }

    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);

        authUser()->deletePushSubscription($request->endpoint);

        return back()->with('delete', 'Notification delete successfully.');
    }
}