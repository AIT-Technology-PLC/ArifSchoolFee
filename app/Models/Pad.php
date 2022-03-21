<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pad extends Model
{
    use MultiTenancy, HasUserstamps, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_approvable',
        'is_closable',
        'is_cancellable',
        'is_printable',
        'has_prices',
        'is_enabled',
    ];

    protected $cascadeDeletes = [
        'padFields',
        'padPermissions',
    ];

    public const INVENTORY_OPERATIONS = [
        'add',
        'subtract',
        'none',
    ];

    public const MODULES = [
        'Warehouse & Inventory',
        'Sales & Customers',
        'Tenders',
        'Purchase & Suppliers',
        'General Settings',
    ];

    public const RELATIONSHIP_TYPES = [
        'Belongs To',
        'Belongs To Many',
        'Has One',
        'Has Many',
    ];

    public function padFields()
    {
        return $this->hasMany(PadField::class);
    }

    public function padPermissions()
    {
        return $this->hasMany(PadPermission::class);
    }

    public function scopeEnabled($query)
    {
        return $query->where('is_enabled', 1);
    }

    public function scopeDisabled($query)
    {
        return $query->where('is_enabled', 0);
    }

    public function isApprovable()
    {
        return $this->is_approvable;
    }

    public function isClosable()
    {
        return $this->is_closable;
    }

    public function isCancellable()
    {
        return $this->is_cancellable;
    }

    public function isPrintable()
    {
        return $this->is_printable;
    }

    public function isEnabled()
    {
        return $this->is_enabled;
    }

    public function getInventoryOperationType()
    {
        return $this->inventory_operation_type;
    }
}
