<?php

namespace App\Models;

use App\Models\Company;
use App\Traits\MultiTenancy;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'properties', 'company_id', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function setPropertiesAttribute($array)
    {
        $properties = [];

        foreach ($array as $item) {
            if (is_null($item['key']) || is_null($item['value'])) {
                continue;
            }

            $properties[] = $item;
        }

        $this->attributes['properties'] = json_encode($properties);
    }

    public function getAll()
    {
        return $this->with(['products', 'createdBy', 'updatedBy'])->orderBy('name')->get();
    }

    public function countProductCategoriesOfCompany()
    {
        return $this->count();
    }
}
