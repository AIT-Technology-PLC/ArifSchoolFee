<?php

namespace App\Models;

use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\Closable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Discountable, Closable, Approvable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'purchased_on' => 'datetime',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function purchaseDetails()
    {
        return $this->hasMany(PurchaseDetail::class);
    }

    public function grns()
    {
        return $this->hasMany(Grn::class);
    }

    public function debits()
    {
        return $this->hasMany(Debit::class);
    }

    public function purchasedBy()
    {
        return $this->belongsTo(User::class, 'purchased_by')->withDefault(['name' => 'N/A']);
    }

    public function scopePurchased($query)
    {
        return $query->whereNotNull('purchased_by');
    }

    public function scopeNotPurchased($query)
    {
        return $query->whereNull('purchased_by');
    }

    public function details()
    {
        return $this->purchaseDetails;
    }

    public function isImported()
    {
        return $this->type == 'Import';
    }

    public function isCashPayment()
    {
        return $this->payment_type == 'Cash Payment';
    }

    public function purchase()
    {
        $this->purchased_by = authUser()->id;

        $this->save();
    }

    public function isPurchased()
    {
        if (is_null($this->purchased_by)) {
            return false;
        }

        return true;
    }
}
