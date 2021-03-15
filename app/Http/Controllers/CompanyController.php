<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompanyController extends Controller
{
    private $company;

    public function __construct(Company $company)
    {
        $this->authorizeResource(Company::class, 'company');

        $this->company = $company;
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $data = $request->validate([
            'sector' => 'nullable|string|max:255',
            'currency' => 'required|string|max:255',
            'email' => 'nullable|string|email|',
            'phone' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'logo' => 'sometimes|file',
        ]);

        DB::transaction(function () use ($company, $data, $request) {
            $company->update($data);

            if ($request->hasFile('logo')) {
                $company->update([
                    'logo' => $request->logo->store('logo', 'public'),
                ]);
            }
        });

        return redirect()->back();
    }
}
