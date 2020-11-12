<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Permission;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    protected $fillable = [
        'user_id', 'company_id', 'permission_id', 'created_by', 'updated_by',
    ];

    protected $with = ['user', 'createdBy', 'updatedBy'];

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

    public function getAll()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->get();
    }

    public function countAllEmployees()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->count();
    }

    public function countEnabledEmployees()
    {
        return $this->where([
            ['company_id', auth()->user()->employee->company_id],
            ['enabled', 1],
        ])->count();
    }

    public function countBlockedEmployees()
    {
        return $this->where([
            ['company_id', auth()->user()->employee->company_id],
            ['enabled', 0],
        ])->count();
    }
}
