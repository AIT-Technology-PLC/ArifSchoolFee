<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;

class AssignFeeMaster extends Model
{
    use MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function feeMaster()
    {
        return $this->belongsTo(FeeMaster::class);
    }
}
