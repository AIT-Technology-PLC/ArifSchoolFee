<?php

namespace App\Models;

use App\Traits\Approvable;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Adjustment extends Model
{
    use HasFactory, Approvable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'is_subtract' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function adjustedBy()
    {
        return $this->belongsTo(User::class, 'adjusted_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function adjustmentDetails()
    {
        return $this->hasMany(AdjustmentDetail::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function scopeCompanyAdjustment($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->companyAdjustment()->latest()->get();
        }

        return $this->companyAdjustment()
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->latest()
            ->get();
    }

    public function adjust()
    {
        $this->adjusted_by = auth()->id();

        $this->save();
    }

    public function isAdjusted()
    {
        if (is_null($this->adjusted_by)) {
            return false;
        }

        return true;
    }
}
