<?php

namespace App\Models;

use App\Traits\Approvable;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Returnn extends Model
{
    use HasFactory, SoftDeletes, Approvable;

    protected $table = "returns";

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

    public function returnedBy()
    {
        return $this->belongsTo(User::class, 'returned_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }

    public function scopeCompanyReturn($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getTotalCreditAttribute()
    {
        $totalPrice = $this->returnDetails
            ->reduce(function ($carry, $item) {
                return $carry + ($item->unit_price * $item->quantity);
            }, 0);

        return $totalPrice;
    }

    public function getTotalCreditAfterVATAttribute()
    {
        return $this->totalCredit * 1.15;
    }

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->companyReturn()->latest()->get();
        }

        return $this->companyReturn()
            ->where('warehouse_id', auth()->user()->warehouse_id)
            ->latest()
            ->get();
    }

    public function add()
    {
        $this->returned_by = auth()->id();

        $this->save();
    }

    public function isAdded()
    {
        if (is_null($this->returned_by)) {
            return false;
        }

        return true;
    }
}
