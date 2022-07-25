<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pad extends Model
{
    use MultiTenancy, HasUserstamps, SoftDeletes, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_approvable' => 'boolean',
        'is_closable' => 'boolean',
        'is_cancellable' => 'boolean',
        'is_printable' => 'boolean',
        'has_prices' => 'boolean',
        'has_payment_term' => 'boolean',
        'is_enabled' => 'boolean',
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

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function convertTo(): Attribute
    {
        return Attribute::make(
            get:fn($value) => !is_null($value) ? explode(',', $value) : [],
            set:fn($value) => implode(',', $value)
        );
    }

    public function convertFrom(): Attribute
    {
        return Attribute::make(
            get:fn($value) => !is_null($value) ? explode(',', $value) : [],
            set:fn($value) => implode(',', $value)
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

    public function isClosable()
    {
        return $this->is_closable;
    }

    public function isClosableOnly()
    {
        return $this->isClosable() && !$this->isApprovable() && $this->isInventoryOperationNone() && !$this->isCancellable();
    }

    public function isCancellable()
    {
        return $this->is_cancellable;
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
        return !$this->isInventoryOperationNone() || $this->isApprovable() || $this->isCancellable();
    }

    public function hasPrices()
    {
        return $this->has_prices;
    }

    public function hasPaymentTerm()
    {
        return $this->has_payment_term;
    }

    public function hasDetailPadFields()
    {
        return $this->padFields()->detailFields()->exists();
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
}
