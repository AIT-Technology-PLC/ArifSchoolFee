<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Subscription;
use Illuminate\Support\Facades\DB;

class ApproveSubscriptionController extends Controller
{
    public function __invoke(Subscription $subscription)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Subscriptions'), 403);

        if ($subscription->isApproved()) {
            return back()->with('failedMessage', 'Subscription is already approved.');
        }

        DB::transaction(function () use ($subscription) {
            $subscription->approve();
        });

        return back()->with('successMessage', 'Subscription is approved successfully.');
    }
}
