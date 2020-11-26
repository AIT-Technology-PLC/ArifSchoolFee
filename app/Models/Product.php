<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['product_category_id', 'company_id', 'supplier_id', 'created_by', 'updated_by', 'name', 'type', 'selling_price', 'purchase_price', 'unit_of_measurement', 'min_on_hand', 'is_expirable', 'properties', 'description'];

    protected $casts = [
        'is_expirable' => 'boolean',
        'properties' => 'array',
    ];

    public function productImages()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function merchandises()
    {
        return $this->hasMany(Merchandise::class);
    }

    public function manufacturings()
    {
        return $this->hasMany(Manufacturing::class);
    }

    public function rawMaterials()
    {
        return $this->hasMany(RawMaterial::class);
    }

    public function billOfMaterial()
    {
        return $this->hasOne(BillOfMaterial::class);
    }

    public function mroItems()
    {
        return $this->hasMany(MroItem::class);
    }

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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
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

    public function getProductNames()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->get(['id', 'name']);
    }

    public function countProductsOfCompany()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->count();
    }
}
