<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EarningCategory extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $cascadeDeletes = ['earnings'];

    public function earnings()
    {
        return $this->hasMany(Earning::class);
    }

    public function isBeforeTax()
    {
        return $this->type == 'Before Tax';
    }
}
