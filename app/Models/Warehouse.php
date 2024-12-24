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
use Haruncpi\LaravelIdGenerator\IdGenerator;

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

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function notices()
    {
        return $this->belongsToMany(Notice::class);
    }

    public function studentHistories()
    {
        return $this->hasMany(StudentHistory::class);
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

    public static function boot()
    {
        parent::boot();
        
        self::creating(function ($model) {
            if ($model->code) {
                $prefix = $model->code;
            } elseif (auth()->check() && userCompany()) {
                $prefix = userCompany()->company_code;
            } else {
                $prefix = 'BR';
            }

            $prefixLength = strlen($prefix) + 1;
            $numericLength = 2;
            $length = $prefixLength + $numericLength;

            $model->code = IdGenerator::generate([
                'table' => 'warehouses', 
                'length' =>  $length, 
                'field' => 'code', 
                'prefix' => $prefix.'/',
                'reset_on_prefix_change' => true,
                ]);
        });
    }
}
