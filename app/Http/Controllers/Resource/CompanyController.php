<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:General Settings');

        $this->authorizeResource(Company::class, 'school');
    }

    public function edit(Company $school)
    {
        $school->load(['schoolType']);

        return view('schools.edit', compact('school'));
    }

    public function update(UpdateCompanyRequest $request, Company $school)
    {
        DB::transaction(function () use ($school, $request) {
            $school->update($request->validated());

            if ($request->hasFile('logo')) {
                $school->update([
                    'logo' => $request->logo->store('logo', 'public'),
                ]);
            }

            if ($request->hasFile('print_template_image')) {
                $school->update([
                    'print_template_image' => $request->print_template_image->store('print_template_image', 'public'),
                ]);
            }
        });

        return back()->with('successMessage', 'Settings updated successfully.');
    }
}
