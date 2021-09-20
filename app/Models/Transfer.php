<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\MultiTenancy;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transfer extends Model
{
    use MultiTenancy, SoftDeletes, Approvable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function subtractedBy()
    {
        return $this->belongsTo(User::class, 'subtracted_by');
    }

    public function addedBy()
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    public function transferredFrom()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_from');
    }

    public function transferredTo()
    {
        return $this->belongsTo(Warehouse::class, 'transferred_to');
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
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

    public function add()
    {
        $this->added_by = auth()->id();

        $this->save();
    }

    public function isAdded()
    {
        if (is_null($this->added_by)) {
            return false;
        }

        return true;
    }

    public function subtract()
    {
        $this->subtracted_by = auth()->id();

        $this->save();
    }

    public function isSubtracted()
    {
        if (is_null($this->subtracted_by)) {
            return false;
        }

        return true;
    }
}
