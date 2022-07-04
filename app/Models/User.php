<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'last_online_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    protected $cascadeDeletes = ['employee'];

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
        return $this->belongsTo(Warehouse::class)->withoutGlobalScopes([ActiveWarehouseScope::class]);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class, 'closed_by');
    }

    public function padPermissions()
    {
        return $this->belongsToMany(PadPermission::class);
    }

    public function isEnabled()
    {
        return $this->employee->enabled;
    }

    public function isAccessAllowed()
    {
        return $this->employee->company->isEnabled() && $this->isEnabled() &&
            ($this->hasRole('System Manager') || $this->warehouse?->isActive());
    }

    public function hasWarehousePermission($type, $warehouses)
    {
        if (is_numeric($warehouses) || $warehouses instanceof Warehouse) {
            return $this->getAllowedWarehouses($type)->contains($warehouses);
        }

        if (!is_array($warehouses)) {
            return false;
        }

        $allowedWarehouseIds = $this->getAllowedWarehouses($type)->pluck('id')->toArray();

        foreach ($warehouses as $warehouse) {
            if (!in_array($warehouse, $allowedWarehouseIds)) {
                return false;
            }
        }

        return true;
    }

    public function getAllowedWarehouses($type)
    {
        $withOnlyCanBeSoldFromBranches = $type == 'sales' ? true : false;

        $cacheKey = $this->id . '_' . $type . '_' . 'allowedWarehouses';

        if ($this->hasRole('System Manager')) {
            return Cache::store('array')
                ->rememberForever($cacheKey, function () use ($withOnlyCanBeSoldFromBranches) {
                    return Warehouse::orderBy('name')
                        ->when($withOnlyCanBeSoldFromBranches, function ($query) {
                            return $query->where('can_be_sold_from', 1);
                        })
                        ->get(['id', 'name']);
                });
        }

        return Cache::store('array')
            ->rememberForever($cacheKey, function () use ($type, $withOnlyCanBeSoldFromBranches) {
                return $this->warehouses()
                    ->wherePivot('type', $type)
                    ->orderBy('warehouses.name')
                    ->when($withOnlyCanBeSoldFromBranches, function ($query) {
                        return $query->where('warehouses.can_be_sold_from', 1);
                    })
                    ->get(['warehouses.id', 'warehouses.name']);
            });
    }
}
