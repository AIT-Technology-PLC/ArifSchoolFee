<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Limit extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function companies()
    {
        return $this->morphedByMany(Company::class, 'limitable');
    }

    public function plans()
    {
        return $this->morphedByMany(Plan::class, 'limitable');
    }
}
