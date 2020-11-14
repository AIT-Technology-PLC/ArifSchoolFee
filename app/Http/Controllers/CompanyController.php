<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    private $company;

    public function __construct(Company $company)
    {
        $this->authorizeResource(Company::class);

        $this->company = $company;
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'sector' => 'nullable|string|max:255',
            'currency' => 'nullable|string|max:255',
        ]);

        $company->update($data);

        return redirect()->back();
    }
}
