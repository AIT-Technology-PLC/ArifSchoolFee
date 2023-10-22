<?php

namespace App\Models;

use App\Models\ProformaInvoice;
use App\Models\Siv;
use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\CalculateCreditPayment;
use App\Traits\Cancellable;
use App\Traits\Closable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\Traits\Subtractable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Gdn extends Model
{
    use MultiTenancy, Branchable, SoftDeletes, Approvable, PricingTicket, HasUserstamps, Subtractable, Addable, CalculateCreditPayment, Closable, Cancellable, HasCustomFields;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'due_date' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function sale()
    {
        return $this->belongsTo(Sale::class);
    }

    public function gdnDetails()
    {
        return $this->hasMany(GdnDetail::class);
    }

    public function reservation()
    {
        return $this->morphOne(Reservation::class, 'reservable');
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

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function returns()
    {
        return $this->hasMany(Returnn::class);
    }

    public function details()
    {
        return $this->gdnDetails;
    }

    public function isConvertedToSale()
    {
        return $this->is_converted_to_sale;
    }

    public function convertToSale()
    {
        $this->is_converted_to_sale = 1;

        $this->save();
    }

    public static function getValidGdnsForReturn($forceIncludedGdn = null)
    {
        return static::query()
            ->subtracted()
            ->notCancelled()
            ->notClosed()
            ->whereRelation('gdnDetails', fn($q) => $q->whereColumn('quantity', '>', 'returned_quantity'))
            ->when(!is_null($forceIncludedGdn), fn($q) => $q->orWhere('id', $forceIncludedGdn))
            ->orderByDesc('code')
            ->get()
            ->groupBy('warehouse_id');
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
}
