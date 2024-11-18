<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateNotificationSettingRequest;
use App\Models\Company;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:General Settings');
    }

    public function edit(Company $school)
    {
        return view('notification-settings.edit', compact('school'));
    }

    public function update(UpdateNotificationSettingRequest $request, Company $school)
    {
        $school->update($request->validated());

        return back()->with('successMessage', 'Notification Setting updated successfully.');
    }
}