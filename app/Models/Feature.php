<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_enabled' => 'boolean',
    ];

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'featurable');
    }

    public function plans()
    {
        return $this->morphedByMany(Plan::class, 'featurable');
    }

    public static function isFeatureAccessible($featureName)
    {
        $feature = (new self())->where('name', $featureName)->first();

        if (!$feature->is_enabled) {
            return false;
        }

        $featureByCompany = $feature->companies()->wherePivot('featurable_id', userCompany()->id)->exists();

        $featureByPlan = $feature->plans()->wherePivot('featurable_id', userCompany()->plan_id)->exists();

        if (!$featureByCompany && !$featureByPlan) {
            return false;
        }

        return true;
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
}
