<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Damage extends Model
{
    use MultiTenancy, Branchable, HasFactory, SoftDeletes, Approvable, HasUserstamps, Subtractable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function damageDetails()
    {
        return $this->hasMany(DamageDetail::class);
    }

    public function details()
    {
        return $this->damageDetails;
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }
}