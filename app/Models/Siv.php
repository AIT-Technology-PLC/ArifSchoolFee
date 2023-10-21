<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Siv extends Model
{
    use MultiTenancy, Branchable, HasFactory, SoftDeletes, Approvable, HasUserstamps, HasCustomFields, Subtractable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function sivDetails()
    {
        return $this->hasMany(SivDetail::class);
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }

    public function canReverseInventoryValuation()
    {
        return false;
    }
}
