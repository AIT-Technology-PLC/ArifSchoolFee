<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = [
        'name', 'sector', 'membership_plan', 'currency',
    ];

    public function employees()
    {
        return $this->hasMany(Models\Employee::class);
    }
}
