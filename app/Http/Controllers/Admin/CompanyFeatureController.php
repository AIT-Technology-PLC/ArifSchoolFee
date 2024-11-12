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
    public function __invoke(UpdateCompanyFeatureRequest $request, Company $school)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        DB::transaction(function () use ($request, $school) {
            $school->features()->syncWithoutDetaching(
                collect($request->validated('enable'))->mapWithKeys(fn($i, $k) => [$i => ['is_enabled' => 1]])->toArray()
            );

            $school->features()->syncWithoutDetaching(
                collect($request->validated('disable'))->mapWithKeys(fn($i, $k) => [$i => ['is_enabled' => 0]])->toArray()
            );

            // Detach if a feature is enabled globally and by plan, and by school
            $school->features()->detach(
                $school->plan->features()->wherePivot('is_enabled', 1)->where('features.is_enabled', 1)->pluck('features.id')->intersect(
                    $school->features()->wherePivot('is_enabled', 1)->pluck('features.id')
                ),
            );

            // Detach if a feature is disabled by school and not owned by plan
            $school->features()->detach(
                $school->features()->wherePivot('is_enabled', 0)->pluck('features.id')->diff(
                    $school->plan->features()->pluck('features.id'),
                )
            );

            // Detach if a feature is disabled globally or by plan, and by school
            $school->features()->detach(
                $school->plan->features()->wherePivot('is_enabled', 0)->orWhere('features.is_enabled', 0)->pluck('features.id')->intersect(
                    $school->features()->wherePivot('is_enabled', 0)->pluck('features.id')
                ),
            );
        });

        return back()->with('successMessage', 'Features have been updated successfully.');
    }
}
