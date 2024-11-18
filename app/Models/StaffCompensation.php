<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;

class StaffCompensation extends Model
{
    use MultiTenancy;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }
}
