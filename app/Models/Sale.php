<?php

namespace App\Models;

use App\Models\ProformaInvoice;
use App\Models\Siv;
use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Cancellable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\Traits\Subtractable;
use App\Utilities\Price;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Approvable, Cancellable, CalculateCreditPayment, Subtractable, Addable, HasCustomFields;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
        'has_withholding' => 'boolean',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function saleDetails()
    {
        return $this->hasMany(SaleDetail::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function credit()
    {
        return $this->morphOne(Credit::class, 'creditable');
    }

    public function proformaInvoice()
    {
        return $this->morphOne(ProformaInvoice::class, 'proforma_invoiceable');
    }

    public function siv()
    {
        return $this->morphOne(Siv::class, 'sivable');
    }

    public function reservation()
    {
        return $this->morphOne(Reservation::class, 'reservable');
    }

    public function details()
    {
        return $this->saleDetails;
    }

    public function hasWithholding()
    {
        return $this->has_withholding;
    }

    public function getTotalWithheldAmountAttribute()
    {
        if (!$this->hasWithholding()) {
            return 0;
        }

        return Price::getTotalWithheldAmount($this->saleDetails);
    }

    public function assignFSNumber($fsNumber)
    {
        $this->fs_number = $fsNumber;

        $this->save();
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }

    public function canReverseInventoryValuation()
    {
        return true;
    }

    public function belongsToTransaction()
    {
        return $this->reservation()->exists() || $this->proformaInvoice()->exists();
    }

    public function isFullyDelivered()
    {
        return $this->saleDetails()->sum('delivered_quantity') == $this->saleDetails()->sum('quantity');
    }
}
