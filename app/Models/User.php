<?php

namespace App\Models;

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
        return $this->hasOne(Employee::class, 'user_id')->withoutGlobalScopes();
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class)->withPivot('type');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function isEnabled()
    {
        return $this->employee->enabled;
    }

    public function hasWarehousePermission($type, $warehouse)
    {
        return $this->getAllowedWarehouses($type)->contains($warehouse);
    }

    public function getAllowedWarehouses($type)
    {
        $withOnlyCanBeSoldFromBranches = $type == 'sales' ? true : false;

        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return Warehouse::orderBy('name')
                ->when($withOnlyCanBeSoldFromBranches, function ($query) {
                    return $query->where('can_be_sold_from', 1);
                })
                ->get(['id', 'name']);
        }

        return $this->warehouses()
            ->wherePivot('type', $type)
            ->orderBy('warehouses.name')
            ->when($withOnlyCanBeSoldFromBranches, function ($query) {
                return $query->where('warehouses.can_be_sold_from', 1);
            })
            ->get(['warehouses.id', 'warehouses.name']);
    }
}
