<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }
}
