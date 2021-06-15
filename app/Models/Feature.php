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

    public function isFeatureAccessible($featureName)
    {
        $feature = $this->where('name', $featureName)->first();

        if (!$feature->is_enabled) {
            return false;
        }

        $featureByCompany = $feature->wherePivot('featurable_id', userCompany()->id)->exists();

        $featureByPlan = $feature->wherePivot('featurable_id', userCompany()->plan_id)->exists();

        if (!$featureByCompany && !$featureByPlan) {
            return false;
        }

        return true;
    }
}
