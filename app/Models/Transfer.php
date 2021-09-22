<?php

namespace App\Models;

use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\Subtractable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transfer extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, HasUserstamps, Branchable, Addable, Subtractable;

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

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this
            ->where(function ($query) {
                $query->where('transferred_from', auth()->user()->warehouse_id)
                    ->orWhere('transferred_to', auth()->user()->warehouse_id);
            })
            ->latest()
            ->get();
    }

    public function countTransfersOfCompany()
    {
        return $this->count();
    }
}
