<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = ['company_id', 'created_by', 'updated_by', 'company_name', 'contact_name', 'email', 'phone', 'country'];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function scopeCompanyCustomers($query)
    {
        return $query->where('company_id', auth()->user()->employee->company_id);
    }

    public function getAll()
    {
        return $this->companyCustomers()->with(['createdBy', 'updatedBy'])->get();
    }

    public function getCustomerNames()
    {
        return $this->companyCustomers()->get(['id', 'company_name']);
    }

    public function countCustomersOfCompany()
    {
        return $this->companyCustomers()->count();
    }
}
