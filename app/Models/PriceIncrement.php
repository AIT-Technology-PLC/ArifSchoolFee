<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PriceIncrement extends Model
{
    use HasFactory, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function PriceIncrementDetails()
    {
        return $this->hasMany(PriceIncrementDetail::class);
    }
}