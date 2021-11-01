<?php

namespace App\Models;

use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Closable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, HasUserstamps, Branchable, Addable, Subtractable, Closable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function transferredFrom()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_from');
    }

    public function transferredTo()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_to');
    }

    public function scopeBranched($query)
    {
        return $query
            ->when(!auth()->user()->hasRole('System Manager'), function ($query) {
                return $query->where('transferred_from', auth()->user()->warehouse_id)
                    ->orWhere('transferred_to', auth()->user()->warehouse_id);
            });
    }

    public static function withBranchScope()
    {
        return false;
    }
}
