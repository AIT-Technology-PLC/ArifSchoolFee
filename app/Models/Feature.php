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

    public static function isFeatureAccessible($featureName)
    {
        $feature = (new self())->where('name', $featureName)->first();

        if (!$feature) {
            return false;
        }

        if (!$feature->is_enabled) {
            return false;
        }

        $featureByCompany = $feature->companies()->wherePivot('featurable_id', userCompany()->id)->first();

        if ($featureByCompany) {
            return $featureByCompany->pivot->is_enabled;
        }

        $featureByPlan = $feature->plans()->wherePivot('featurable_id', userCompany()->plan_id)->first();

        if ($featureByPlan) {
            return $featureByPlan->pivot->is_enabled;
        }

        return false;
    }

    public static function enableOrDisable($featureName, $value)
    {
        (new self())->where('name', $featureName)
            ->firstOrFail()
            ->update([
                'is_enabled' => $value,
            ]);
    }

    public static function status($featureName)
    {
        $feature = (new self())->where('name', $featureName)->first();

        if ($feature->is_enabled) {
            return 'Enabled';
        }

        return 'Disabled';
    }

    public static function enableOrDisableForCompany($featureName, $companyId, $value)
    {
        $feature = (new self())->where('name', $featureName)->firstOrFail();

        $company = Company::where('id', $companyId)->firstOrFail();

        DB::transaction(function () use ($feature, $company, $value) {
            $feature->companies()->syncWithoutDetaching([
                $company->id => ['is_enabled' => $value],
            ]);
        });
    }

    public static function deleteForCompany($featureName, $companyId)
    {
        $feature = (new self())->where('name', $featureName)->firstOrFail();

        $company = Company::where('id', $companyId)->firstOrFail();

        $feature->companies()->detach($company);
    }

    public static function enableForPlan($featureName, $planName)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $plan = Plan::firstWhere('name', $planName);

        $feature->plans()->updateExistingPivot($plan->id, ['is_enabled' => 1]);
    }

    public static function disableForPlan($featureName, $planName)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $plan = Plan::firstWhere('name', $planName);

        $feature->plans()->updateExistingPivot($plan->id, ['is_enabled' => 0]);
    }

    public static function getAllEnabledFeaturesOfCompany()
    {
        $globallyDisabledfeatures = (new self())->where('is_enabled', 0)->pluck('name');

        $planFeatures = userCompany()
            ->plan
            ->features()
            ->wherePivot('is_enabled', 1)
            ->pluck('name');

        $companyFeatures = userCompany()
            ->features()
            ->wherePivot('is_enabled', 1)
            ->pluck('name');

        $disabledCompanyFeatures = userCompany()
            ->features()
            ->wherePivot('is_enabled', 0)
            ->pluck('name');

        return $planFeatures
            ->merge($companyFeatures)
            ->unique()
            ->diff($disabledCompanyFeatures)
            ->diff($globallyDisabledfeatures);
    }
}
