<?php

namespace App\Models;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    protected $fillable = ['settings', 'warehouses', 'products', 'merchandises', 'manufacturings'];

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
}
