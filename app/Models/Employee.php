<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Permission;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id', 'company_id', 'permission_id', 'created_by', 'updated_by', 'enabled', 'position',
    ];

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

    public function permission()
    {
        return $this->belongsTo(Permission::class);
    }

    public function scopeCompanyEmployees($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAll()
    {
        return $this->companyEmployees()->with(['user', 'permission', 'createdBy', 'updatedBy'])->get();
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
