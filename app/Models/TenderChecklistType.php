<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TenderChecklistType extends Model
{
    use HasFactory, SoftDeletes;

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

    public function generalTenderChecklists()
    {
        return $this->hasMany(GeneralTenderChecklist::class);
    }

    public function scopeCompanyTenderChecklistType($query)
    {
        return $query->where('company_id', userCompany()->id);
    }
}
