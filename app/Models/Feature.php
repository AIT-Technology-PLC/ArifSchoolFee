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

    public static function disable($featureName)
    {
        (new self())->where('name', $featureName)->update([
            'is_enabled' => 0,
        ]);
    }

    public static function enable($featureName)
    {
        (new self())->where('name', $featureName)->update([
            'is_enabled' => 1,
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

    public static function enableForCompany($featureName, $companyId)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $company = Company::find($companyId);

        DB::transaction(function () use ($feature, $company) {
            $feature->companies()->detach($company);

            $feature->companies()->attach($company, ['is_enabled' => 1]);
        });
    }

    public static function disableForCompany($featureName, $companyId)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $company = Company::find($companyId);

        DB::transaction(function () use ($feature, $company) {
            $feature->companies()->detach($company);

            $feature->companies()->attach($company, ['is_enabled' => 0]);
        });
    }

    public static function enableForPlan($featureName, $planId)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $plan = Plan::find($planId);

        DB::transaction(function () use ($feature, $plan) {
            $feature->plans()->detach($plan);

            $feature->plans()->attach($plan, ['is_enabled' => 1]);
        });
    }

    public static function disableForPlan($featureName, $planId)
    {
        $feature = (new self())->where('name', $featureName)->first();

        $plan = Plan::find($planId);

        DB::transaction(function () use ($feature, $plan) {
            $feature->plans()->detach($plan);

            $feature->plans()->attach($plan, ['is_enabled' => 0]);
        });
    }
}
