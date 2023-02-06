<?php

namespace App\Models;

use App\Models\Expense;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tax extends Model
{
    use MultiTenancy, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function isVat()
    {
        return $this->type == 'VAT';
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}