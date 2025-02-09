<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreSubscriptionRequest;
use App\Http\Requests\Admin\UpdateSubscriptionRequest;
use App\Models\Company;
use App\Models\Plan;
use App\Models\Subscription;

class CompanySubscriptionController extends Controller
{
    public function store(StoreSubscriptionRequest $request, Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Subscriptions'), 403);

        if (!$school->canCreateNewSubscription()) {
            return back()->with('failedMessage', 'Subscription can be created if current subscription has less than 30 days left.');
        }

        $school->subscriptions()->create($request->validated());

        return back()->with('successMessage', 'New subscription is created.');
    }

    public function edit(Subscription $subscription)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Subscriptions'), 403);

        if ($subscription->isApproved()) {
            return back()->with('failedMessage', 'Approved subscriptions can not be edited.');
        }

        $plans = Plan::enabled()->get()->push($subscription->company->plan)->unique();

        return view('admin.subscriptions.edit', compact('subscription', 'plans'));
    }

    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Subscriptions'), 403);

        if ($subscription->isApproved()) {
            return back()->with('failedMessage', 'Approved subscriptions can not be edited.');
        }

        $subscription->update($request->validated());

        return redirect()->route('admin.companies.show', $subscription->company)->with('successMessage', 'New subscription is created.');
    }

    public function destroy(Subscription $subscription)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Subscriptions'), 403);

        if ($subscription->isApproved()) {
            return back()->with('failedMessage', 'Approved subscriptions can not be deleted.');
        }

        $subscription->forceDelete();

        return back()->with('failedMessage', 'Subscription is deleted successfully.');
    }
}
