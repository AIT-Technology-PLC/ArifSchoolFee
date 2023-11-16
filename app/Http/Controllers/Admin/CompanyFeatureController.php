<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCompanyFeatureRequest;
use App\Models\Company;
use Illuminate\Support\Facades\DB;

class CompanyFeatureController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UpdateCompanyFeatureRequest $request, Company $company)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        DB::transaction(function () use ($request, $company) {
            $company->features()->syncWithoutDetaching(
                collect($request->validated('enable'))->mapWithKeys(fn($i, $k) => [$i => ['is_enabled' => 1]])->toArray()
            );

            $company->features()->syncWithoutDetaching(
                collect($request->validated('disable'))->mapWithKeys(fn($i, $k) => [$i => ['is_enabled' => 0]])->toArray()
            );

            // Detach if a feature is enabled globally and by plan, and by company
            $company->features()->detach(
                $company->plan->features()->wherePivot('is_enabled', 1)->where('features.is_enabled', 1)->pluck('features.id')->intersect(
                    $company->features()->wherePivot('is_enabled', 1)->pluck('features.id')
                ),
            );

            // Detach if a feature is disabled by company and not owned by plan
            $company->features()->detach(
                $company->features()->wherePivot('is_enabled', 0)->pluck('features.id')->diff(
                    $company->plan->features()->pluck('features.id'),
                )
            );

            // Detach if a feature is disabled globally or by plan, and by company
            $company->features()->detach(
                $company->plan->features()->wherePivot('is_enabled', 0)->orWhere('features.is_enabled', 0)->pluck('features.id')->intersect(
                    $company->features()->wherePivot('is_enabled', 0)->pluck('features.id')
                ),
            );
        });

        return back()->with('successMessage', 'Features have been updated successfully.');
    }
}
