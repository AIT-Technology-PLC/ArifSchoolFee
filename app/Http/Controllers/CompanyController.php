<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    public function __construct()
    {
        $this->authorizeResource(Company::class, 'company');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(UpdateCompanyRequest $request, Company $company)
    {
        DB::transaction(function () use ($company, $request) {
            $company->update($request->all());

            if ($request->hasFile('logo')) {
                $company->update([
                    'logo' => $request->logo->store('logo', 'public'),
                ]);
            }
        });

        return redirect()->back();
    }
}
