<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Models\Company;

class ProductCategory extends Model
{
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

    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }

    public function getAll()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->get();
    }
}
