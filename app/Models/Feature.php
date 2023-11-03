<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'featurable')->withPivot('is_enabled');
    }

    public function plans()
    {
        return $this->morphedByMany(Plan::class, 'featurable')->withPivot('is_enabled');
    }

    public static function enableOrDisable($featureName, $value)
    {
        static::where('name', $featureName)
            ->firstOrFail()
            ->update([
                'is_enabled' => $value,
            ]);
    }

    public static function status($featureName)
    {
        $feature = static::where('name', $featureName)->firstOrFail();

        if ($feature->is_enabled) {
            return 'Enabled';
        }

        return 'Disabled';
    }

    public static function enableOrDisableForCompany($featureName, $companyId, $value)
    {
        $feature = static::where('name', $featureName)->firstOrFail();

        $company = Company::where('id', $companyId)->firstOrFail();

        DB::transaction(function () use ($feature, $company, $value) {
            $feature->companies()->syncWithoutDetaching([
                $company->id => ['is_enabled' => $value],
            ]);
        });
    }

    public static function deleteForCompany($featureName, $companyId)
    {
        $feature = static::where('name', $featureName)->firstOrFail();

        $company = Company::where('id', $companyId)->firstOrFail();

        $feature->companies()->detach($company);
    }

    public static function enableOrDisableForPlan($featureName, $planName, $value)
    {
        $feature = static::where('name', $featureName)->firstOrFail();

        $plan = Plan::where('name', $planName)->firstOrFail();

        $feature->plans()->updateExistingPivot($plan->id, ['is_enabled' => $value]);
    }

    public static function getAllEnabledFeaturesOfCompany($companyId = null)
    {
        if (!is_null($companyId)) {
            $company = Company::find($companyId);
        }

        if (auth()->check() && !authUser()->isAdmin()) {
            $company = userCompany();
        }

        if (!isset($company)) {
            return collect();
        }

        $enabledFeatures = DB::table('featurables')
            ->join('features', 'featurables.feature_id', 'features.id')
            ->where('features.is_enabled', 1)
            ->where(function ($query) use ($company) {
                $query->where('featurables.featurable_type', 'App\\Models\\Plan')
                    ->where('featurables.featurable_id', $company->plan_id)
                    ->where('featurables.is_enabled', 1);
            })
            ->orWhere(function ($query) use ($company) {
                $query->where('featurables.featurable_type', 'App\\Models\\Company')
                    ->where('featurables.featurable_id', $company->id)
                    ->where('featurables.is_enabled', 1);
            })
            ->pluck('features.name')
            ->unique();

        $disabledFeaturesForCompany = DB::table('featurables')
            ->join('features', 'featurables.feature_id', 'features.id')
            ->where('featurables.featurable_type', 'App\\Models\\Company')
            ->where('featurables.featurable_id', $company->id)
            ->where('featurables.is_enabled', 0)
            ->pluck('features.name')
            ->unique();

        return $enabledFeatures->diff($disabledFeaturesForCompany);
    }
}
