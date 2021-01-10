<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Siv extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function sivable()
    {
        return $this->morphTo();
    }

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

    public function sivDetails()
    {
        return $this->hasMany(SivDetails::class);
    }

    public function scopeCompanySivs($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAll()
    {
        return $this->companySivs()->latest()->get();
    }
}
