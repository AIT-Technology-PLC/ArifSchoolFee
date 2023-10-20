<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function limits()
    {
        return $this->morphToMany(Limit::class, 'limitable')->withPivot('amount');
    }

    public function features()
    {
        return $this->morphToMany(Feature::class, 'featurable')->withPivot('is_enabled');
    }
}
