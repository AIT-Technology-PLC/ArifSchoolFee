<?php

namespace App\Models;

use App\Scopes\ActiveWarehouseScope;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Cache;
use Laravel\Sanctum\HasApiTokens;
use NotificationChannels\WebPush\HasPushSubscriptions;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes, HasRoles, CascadeSoftDeletes, HasPushSubscriptions, HasApiTokens;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'last_online_at'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
        'password' => 'hashed',
        'is_allowed' => 'boolean',
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

    public function sideMenuComponent(): Attribute
    {
        return Attribute::get(fn() => $this->isAdmin() ? 'common.admin-side-menu' : ($this->isCallCenter() || $this->isBank() ? 'common.call-center-side-menu' : 'common.side-menu'));
    }

    public function scopeAllowed($query)
    {
        return $query->where('is_allowed', 1);
    }

    public function scopeNotAllowed($query)
    {
        return $query->where('is_allowed', 0);
    }

    public function isAllowed()
    {
        return $this->is_allowed;
    }

    public function isEnabled()
    {
        return $this->employee->enabled;
    }

    public function isAdmin()
    {
        return $this->is_admin && is_null($this->employee);
    }

    public function isCallCenter()
    {
        return $this->user_type == 'call_center' && is_null($this->employee);
    }

    public function isBank()
    {
        return $this->user_type == 'bank' && is_null($this->employee);
    }

    public function isAccessAllowed()
    {
        if ($this->isAdmin() || $this->isCallCenter() || $this->isBank()) {
            if ($this->isAllowed()) {
                return true;
            }
            
            return false;
        }

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
                    ->get(['warehouses.id', 'warehouses.name'])
                    ->push($this->warehouse)
                    ->unique();
            });
    }

    public static function getUsers()
    {
        return static::query()
            ->whereIn('warehouse_id', authUser()->getAllowedWarehouses('hr')->pluck('id'))
            ->with('employee')
            ->orderBy('name')
            ->get();
    }

    public function updateLastOnlineAt()
    {
        $this->last_online_at = now();

        $this->save();
    }

    public function updateUserLoginLog()
    {
        UserLog::Create(
            [
                'created_by' => $this->id,
                'updated_by' => $this->id,
                'company_id' => userCompany()->id ?? null,
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->employee->phone,
                'role' => $this->roles[0]->name,
                'ip_address' => request()->ip(),
                'last_online_at' => now(),
            ]
        );
    }

    public function toggle()
    {
        $this->isAllowed() ? $this->is_allowed = 0 : $this->is_allowed = 1;

        $this->save();
    }
}
