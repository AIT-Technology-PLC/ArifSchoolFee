<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Transfer extends Model
{
    use SoftDeletes;

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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function transferDetails()
    {
        return $this->hasMany(TransferDetail::class);
    }

    public function scopeCompanyTransfer($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
    }

    public function getAll()
    {
        return $this->companyTransfer()->latest()->get();
    }

    public function countTransfersOfCompany()
    {
        return $this->companyTransfer()->count();
    }

    public function changeStatusToTransferred()
    {
        $this->status = 'Transferred';
        $this->save();
    }

    public function isTransferDone()
    {
        return $this->status == 'Transferred';
    }

    public function approveTransfer()
    {
        $this->approved_by = auth()->id();

        $this->save();
    }

    public function isTransferApproved()
    {
        if ($this->approved_by) {
            return true;
        }

        return false;
    }
}
