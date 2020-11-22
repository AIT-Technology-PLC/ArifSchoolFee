<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'shipped_at' => datetime,
        'delivered_at' => datetime,
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

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function getAll()
    {
        return $this->with(['createdBy', 'updatedBy', 'supplier', 'product'])
            ->where('company_id', auth()->user()->employee->company_id)->get();
    }

    public function countPurchasesOfCompany()
    {
        return $this->where('company_id', auth()->user()->employee->company_id)->count();
    }
}
