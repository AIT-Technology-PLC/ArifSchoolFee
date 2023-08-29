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

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function tagType(): Attribute
    {
        return Attribute::get(
            fn($value) => $this->tag == 'input' ? $value : null
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

    public function isTagInput()
    {
        return str()->lower($this->tag) == 'input';
    }

    public function isTagSelect()
    {
        return str()->lower($this->tag) == 'select';
    }

    public function isUnique()
    {
        return $this->is_unique;
    }
}
