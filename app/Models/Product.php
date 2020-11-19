<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_category_id', 'company_id', 'created_by', 'updated_by', 'name', 'type', 'selling_price', 'purchase_price', 'unit_of_measurement', 'min_on_hand', 'is_expirable', 'properties', 'description'];

    protected $casts = [
        'is_expirable' => 'boolean',
        'properties' => 'array',
    ];

    public function productCategory()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
        return $this->with(['productCategory', 'createdBy', 'updatedBy'])->where('company_id', auth()->user()->employee->company_id)->get();
    }

    public function countProductsOfCompany()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->count();
    }
}
