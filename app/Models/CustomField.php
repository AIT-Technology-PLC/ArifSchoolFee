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

    public function modelType(): Attribute
    {
        return Attribute::get(
            fn($value) => new $value()
        );
    }

    public function tagType(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->tag == 'input' ? $value : null
        );
    }

    public function options(): Attribute
    {
        return Attribute::make(
            fn($value) => !is_null($value) ? explode(',', $value) : [],
            fn($value) => implode(',', $value)
        );
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

    public function isMaster()
    {
        return $this->is_master;
    }
}
