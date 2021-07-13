<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'published_on' => 'datetime',
        'closing_date' => 'datetime',
        'opening_date' => 'datetime',
        'clarify_on' => 'datetime',
        'visit_on' => 'datetime',
        'premeet_on' => 'datetime',
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

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tenderDetails()
    {
        return $this->hasMany(TenderDetail::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklist::class);
    }

    public function scopeCompanyTender($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getAll()
    {
        return $this->companyTender()->withCount('tenderDetails')->latest()->get();
    }

    public function countTendersOfCompany()
    {
        return $this->companyTender()->count();
    }

    public function getTenderChecklistsCompletionRate()
    {
        $completedChecklists = $this->tenderChecklists->where('status', 'Completed')->count();
        $inProcessChecklists = $this->tenderChecklists->where('status', 'In Process')->count();
        $totalChecklists = $this->tenderChecklists->count();

        if (!$totalChecklists) {
            return 0;
        }

        if ($completedChecklists == $totalChecklists) {
            return 100.00;
        }

        $inProcessChecklists = $inProcessChecklists * 0.5;
        $checklistCompletionRate = ($completedChecklists + $inProcessChecklists) / $totalChecklists;

        return number_format($checklistCompletionRate * 100, 2);
    }
}
