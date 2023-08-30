<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomField extends Model
{
    use HasFactory, SoftDeletes, MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function customFieldValues()
    {
        return $this->hasMany(CustomFieldValue::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function scopePrintable($query)
    {
        return $query->where('is_printable', 1);
    }

    public function scopeVisibleOnColumns($query)
    {
        return $query->where('is_visible', 1);
    }

    public function icon(): Attribute
    {
        return Attribute::get(fn($value) => is_null($value) ? 'fas fa-file' : $value);
    }

    public function columnSize(): Attribute
    {
        return Attribute::get(fn($value) => is_null($value) ? 'is-6' : $value);
    }

    public function isActive()
    {
        return $this->is_active;
    }

    public function isRequired()
    {
        return $this->is_required;
    }

    public function isVisible()
    {
        return $this->is_visible;
    }

    public function isPrintable()
    {
        return $this->is_printable;
    }

    public function hasOptions()
    {
        if (is_null($this->options) || !str($this->options)->contains(',')) {
            return [];
        }

        return explode(',', $this->options);
    }

    public function isUnique()
    {
        return $this->is_unique;
    }
}
