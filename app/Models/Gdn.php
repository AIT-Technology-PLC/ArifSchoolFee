<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Gdn extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'is_approved' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function scopeCompanyGdn($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, auth()->user()->employee->company->id . '_');
    }

    public function getAll()
    {
        return $this->companyGdn()->withCount('gdnDetails')->latest()->get();
    }

    public function countGdnsOfCompany()
    {
        return $this->companyGdn()->count();
    }

    public function changeStatusToSubtractedFromInventory()
    {
        $this->status = 'Subtracted From Inventory';
        $this->save();
    }

    public function isGdnSubtracted()
    {
        return $this->status == 'Subtracted From Inventory';
    }

    public function approveGdn()
    {
        $this->is_approved = 1;

        $this->save();
    }

    public function isGdnApproved()
    {
        return $this->is_approved;
    }
}
