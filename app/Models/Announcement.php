<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Announcement extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, Approvable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }
}
