<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Advancement extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, Approvable, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($advancement) {
            if (auth()->check()) {
                $advancement['code'] = nextReferenceNumber('advancements');
            }
        });
    }

    public function advancementDetails()
    {
        return $this->hasMany(AdvancementDetail::class);
    }

    public function isPromotion()
    {
        return $this->type == 'Promotion';
    }
}
