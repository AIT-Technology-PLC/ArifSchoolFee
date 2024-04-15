<?php

namespace App\Models;

use App\Models\Exchange;
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
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory, MultiTenancy, Branchable, SoftDeletes, HasUserstamps, PricingTicket, Approvable, Cancellable, CalculateCreditPayment, Subtractable, Addable, HasCustomFields;

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

    public function sivs()
    {
        return $this->morphMany(Siv::class, 'sivable');
    }

    public function reservation()
    {
        return $this->morphOne(Reservation::class, 'reservable');
    }

    public function deliveredPercentage(): Attribute
    {
        return Attribute::get(
            fn() => number_format($this->saleDetails()->selectRaw('SUM(delivered_quantity/quantity)*100 AS average')->value('average'), 1)
        );
    }

    public function scopeDelivered($query)
    {
        return $query->has('saleDetails')->whereDoesntHave('saleDetails', fn($q) => $q->whereColumn('quantity', '<>', 'delivered_quantity'));
    }

    public function scopePartiallyDelivered($query)
    {
        return $query
            ->whereHas('saleDetails', fn($q) => $q->where('delivered_quantity', '>', 0)->whereColumn('delivered_quantity', '<', 'quantity'));
    }

    public function scopeNotDelivered($query)
    {
        return $query
            ->has('saleDetails')
            ->whereDoesntHave('saleDetails', fn($q) => $q->where('delivered_quantity', '>', 0));
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
        return $this->saleDetails()->exists() && $this->saleDetails()->whereColumn('quantity', '<>', 'delivered_quantity')->doesntExist();
    }

    public function isPartiallyDelivered()
    {
        $deliveredQuantity = $this->saleDetails()->sum('delivered_quantity');

        return ($this->saleDetails()->sum('quantity') > $deliveredQuantity) && $deliveredQuantity > 0;
    }

    public function exchange()
    {
        return $this->morphOne(Exchange::class, 'exchangeable');
    }

    public static function getValidSalesForReturn($forceIncludedSale = null)
    {
        return static::query()
            ->subtracted()
            ->notCancelled()
            ->when(!is_null($forceIncludedSale), fn($q) => $q->orWhere('id', $forceIncludedSale))
            ->orderByDesc('code')
            ->get()
            ->groupBy('warehouse_id');
    }
}
