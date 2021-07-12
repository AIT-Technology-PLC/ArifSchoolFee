<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GeneralTenderChecklist extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

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

    public function tenderChecklistType()
    {
        return $this->belongsTo(TenderChecklistType::class);
    }

    public function scopeCompanyGeneralTenderChecklist($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyGeneralTenderChecklist()->orderBy('item', 'asc')->get();
    }
}
