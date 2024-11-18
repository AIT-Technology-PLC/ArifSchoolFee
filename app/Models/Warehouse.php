<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Warehouse extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected $cascadeDeletes = [
        'originalUsers',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Branch');
    }

    public static function booted()
    {
        static::addGlobalScope(new ActiveWarehouseScope);
    }

    public function originalUsers()
    {
        return $this->hasMany(User::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class)->withPivot('type');
    }

    public function staffs()
    {
        return $this->hasMany(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->withoutGlobalScopes([ActiveWarehouseScope::class])->where('is_active', 0);
    }

    public function isActive()
    {
        return $this->is_active;
    }
}
