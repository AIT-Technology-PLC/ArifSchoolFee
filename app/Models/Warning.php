<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Warning extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, Approvable, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    protected static function booted()
    {
        static::creating(function ($warning) {
            if (auth()->check()) {
                $warning['code'] = nextReferenceNumber('warnings');
            }
        });
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
