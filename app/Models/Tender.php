<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tender extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'published_on' => 'datetime',
        'closing_date' => 'datetime',
        'opening_date' => 'datetime',
        'clarify_on' => 'datetime',
        'visit_on' => 'datetime',
        'premeet_on' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function tenderLots()
    {
        return $this->hasMany(TenderLot::class);
    }

    public function tenderChecklists()
    {
        return $this->hasMany(TenderChecklist::class);
    }

    public function getTenderChecklistsCompletionRateAttribute()
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
