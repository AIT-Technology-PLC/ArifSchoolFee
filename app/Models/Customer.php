<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, CascadeSoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $cascadeDeletes = ['credits'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function tenders()
    {
        return $this->hasMany(Tender::class);
    }

    public function proformaInvoices()
    {
        return $this->hasMany(ProformaInvoice::class);
    }

    public function returns()
    {
        return $this->hasMany(Returnn::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function credits()
    {
        return $this->hasMany(Credit::class);
    }

    public function tenderOpportunities()
    {
        return $this->hasMany(TenderOpportunity::class);
    }

    public function hasReachedCreditLimit($newCreditAmount, $excludedCreditId = null)
    {
        if ($this->credit_amount_limit == 0) {
            return false;
        }

        $totalCreditAmount = $this->credits()
            ->when($excludedCreditId, fn($query) => $query->where('id', '<>', $excludedCreditId))
            ->sum('credit_amount');

        $totalCreditAmountSettled = $this->credits()
            ->when($excludedCreditId, fn($query) => $query->where('id', '<>', $excludedCreditId))
            ->sum('credit_amount_settled');

        $currentCreditAmount = $totalCreditAmount - $totalCreditAmountSettled;

        if (($currentCreditAmount + $newCreditAmount) > $this->credit_amount_limit) {
            return true;
        }

        return false;
    }

    public function billOfMaterials()
    {
        return $this->hasMany(BillOfMaterial::class);
    }

    public function getCreditByPeriod($a, $b = null)
    {
        $credits = $this->credits()->unSettled()->where(now()->diffInDays($this->due_date) > $a)->when(!is_null($b), fn($q) => $q->where((now()->diffInDays($this->due_date) < $b))->get();

        return $credits->sum('credit_amount') - $credits->sum('credit_amount_settled');

        // if (now()->diffInDays($this->due_date) < 30) {
        //     return $this->creditAmountUnsettled;
        // }
    }
}
