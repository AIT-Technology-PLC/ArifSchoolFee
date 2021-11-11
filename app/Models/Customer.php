<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class);
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

    public function hasReachedCreditLimit($newCreditAmount, $excludedCreditId = null)
    {
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
}
