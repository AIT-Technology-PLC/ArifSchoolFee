<?php

namespace App\Models;

use App\Models\Company;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
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

    public function scopeCompanyEmployees($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyEmployees()->with(['user.roles', 'createdBy', 'updatedBy'])->get();
    }

    public function countAllEmployees()
    {
        return $this->companyEmployees()->count();
    }

    public function countEnabledEmployees()
    {
        return $this->companyEmployees()->where('enabled', 1)->count();
    }

    public function countBlockedEmployees()
    {
        return $this->companyEmployees()->where('enabled', 0)->count();
    }
}
