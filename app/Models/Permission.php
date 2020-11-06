<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['user', 'settings', 'warehouses', 'products', 'merchandises', 'manufacturings'];
    
    public function employee()
    {
        return $this->hasOne(Models\Employee::class);
    }
}
