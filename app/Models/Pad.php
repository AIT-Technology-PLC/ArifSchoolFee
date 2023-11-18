<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

class Pad extends Model
{
    use MultiTenancy, HasUserstamps, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_approvable' => 'boolean',
        'is_printable' => 'boolean',
        'has_prices' => 'boolean',
        'is_enabled' => 'boolean',
    ];

    protected $cascadeDeletes = [
        'padFields',
        'padPermissions',
        'padStatuses',
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


    public const COMPONENTS = [
        'supplier',
        'customer',
        'user',
        'warehouse',
        'product',
        'contact',
    ];

    public function padFields()
    {
        return $this->hasMany(PadField::class);
    }

    public function padStatuses()
    {
        return $this->hasMany(PadStatus::class);
    }

    public function padPermissions()
    {
        return $this->hasMany(PadPermission::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function convertTo(): Attribute
    {
        return Attribute::make(
            get: fn($value) => !is_null($value) ? explode(',', $value) : [],
            set: fn($value) => implode(',', $value)
        );
    }

    public function convertFrom(): Attribute
    {
        return Attribute::make(
            get: fn($value) => !is_null($value) ? explode(',', $value) : [],
            set: fn($value) => implode(',', $value)
        );
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

    public function isPrintable()
    {
        return $this->is_printable;
    }

    public function isConvertable()
    {
        return count($this->convert_to) || count($this->convert_from);
    }

    public function isEnabled()
    {
        return $this->is_enabled;
    }

    public function getInventoryOperationType()
    {
        return $this->inventory_operation_type;
    }

    public function isInventoryOperationAdd()
    {
        return $this->inventory_operation_type == 'add';
    }

    public function isInventoryOperationSubtract()
    {
        return $this->inventory_operation_type == 'subtract';
    }

    public function isInventoryOperationNone()
    {
        return $this->inventory_operation_type == 'none';
    }

    public function hasStatus()
    {
        return !$this->isInventoryOperationNone() || $this->isApprovable();
    }

    public function hasPrices()
    {
        return $this->has_prices;
    }

    public function hasDetailPadFields()
    {
        $count = $this->padFields()->detailFields()->count();

        if ($this->isInventoryOperationAdd() && $count == 2) {
            return false;
        }

        if (!$this->isInventoryOperationAdd() && $count == 1) {
            return false;
        }

        return $count;
    }

    public function converts()
    {
        return $this
            ->enabled()
            ->pluck('name')
            ->merge([
                'grns',
                'sivs',
                'sales',
                'gdns',
                'proforma-invoices',
            ]);
    }

    public function featureNames($feature)
    {
        $featureNames = [
            'grns' => 'GRN',
            'sivs' => 'SIV',
            'sales' => 'Invoice',
            'gdns' => 'Delivery Order',
            'proforma-invoices' => 'Proforma Invoice',
        ];

        return $featureNames[$feature] ?? Arr::first($this->convert_to, fn($item) => $item == $feature) ?? null;
    }
}
