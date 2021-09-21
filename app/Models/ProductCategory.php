<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $fillable = [
        'name', 'description', 'properties', 'company_id', 'created_by', 'updated_by',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

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
