<?php

namespace App\Models;

use App\Models\CostUpdateDetail;
use App\Traits\Approvable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Rejectable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CostUpdate extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, Approvable, HasUserstamps, Rejectable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function costUpdateDetails()
    {
        return $this->hasMany(CostUpdateDetail::class);
    }
}
