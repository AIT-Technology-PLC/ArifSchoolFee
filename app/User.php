<?php

namespace App;

use App\Models as Models;
use App\Models\Warehouse;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'last_online_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Models\Employee::class, 'user_id')->withoutGlobalScopes();
    }

    public function warehouses()
    {
        return $this->belongsToMany(Models\Warehouse::class)->withPivot('type');
    }

    public function warehouse()
    {
        return $this->belongsTo(Models\Warehouse::class);
    }

    public function isEnabled()
    {
        return $this->employee->enabled;
    }

    public function readWarehouses()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return (new Warehouse())->getAll()->pluck('id');
        }

        return $this->warehouses()->wherePivot('type', 'read')->pluck('warehouse_id');
    }

    public function addWarehouses()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return (new Warehouse())->getAll()->pluck('id');
        }

        return $this->warehouses()->wherePivot('type', 'add')->pluck('warehouse_id');
    }

    public function subtractWarehouses()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return (new Warehouse())->getAll()->pluck('id');
        }

        return $this->warehouses()->wherePivot('type', 'subtract')->pluck('warehouse_id');
    }

    public function assignedWarehouse()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return (new Warehouse())->getAll()->pluck('id');
        }

        return $this->warehouse_id;
    }
}
