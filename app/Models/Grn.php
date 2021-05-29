<?php

namespace App\Models;

use App\Traits\Approvable;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Grn extends Model
{
    use SoftDeletes, Approvable;

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

    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function grnDetails()
    {
        return $this->hasMany(GrnDetail::class);
    }

    public function scopeCompanyGrn($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getAll()
    {
        return $this->companyGrn()->latest()->get();
    }

    public function countGrnsOfCompany()
    {
        return $this->companyGrn()->count();
    }

    public function changeStatusToAddedToInventory()
    {
        $this->status = 'Added To Inventory';
        $this->save();
    }

    public function isAddedToInventory()
    {
        return $this->status == 'Added To Inventory';
    }
}
