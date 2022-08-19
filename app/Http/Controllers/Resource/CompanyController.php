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

        $this->authorizeResource(Company::class, 'company');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        DB::transaction(function () use ($company, $request) {
            $company->update($request->validated());

            if ($request->hasFile('logo')) {
                $company->update([
                    'logo' => $request->logo->store('logo', 'public'),
                ]);
            }

            if ($request->hasFile('print_template_image')) {
                $company->update([
                    'print_template_image' => $request->print_template_image->store('print_template_image', 'public'),
                ]);
            }
        });

        return back()->with('successMessage', 'Settings updated successfully.');
    }
}
