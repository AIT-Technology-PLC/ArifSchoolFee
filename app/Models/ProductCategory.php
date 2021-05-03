<?php

namespace App\Models;

use App\Models\Company;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;

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

    public function company()
    {
        return $this->belongsTo(Company::class);
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

    public function scopeCompanyCategories($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyCategories()->with(['products', 'createdBy', 'updatedBy'])->orderBy('name')->get();
    }

    public function countProductCategoriesOfCompany()
    {
        return $this->companyCategories()->count();
    }
}
