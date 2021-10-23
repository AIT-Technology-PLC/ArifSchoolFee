<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProformaInvoice extends Model
{
    use MultiTenancy, HasFactory, SoftDeletes, PricingTicket, Discountable, HasUserstamps, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'expires_on' => 'datetime',
        'is_pending' => 'boolean',
    ];

    protected $attributes = [
        'is_pending' => 1,
    ];

    public function convertedBy()
    {
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function proformaInvoiceDetails()
    {
        return $this->hasMany(ProformaInvoiceDetail::class);
    }

    public function getReferenceAttribute()
    {
        $prefix = $this->prefix;

        if (!$prefix) {
            return $this->code;
        }

        if (Str::endsWith($prefix, '/')) {
            $prefix = Str::of($prefix)->replaceLast('/', '');
        }

        return Str::of($prefix)->append('/', $this->code);
    }

    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_by');
    }

    public function scopeNotConverted($query)
    {
        return $query->whereNull('converted_by');
    }

    public function scopePending($query)
    {
        return $query->where('is_pending', 1);
    }

    public function scopeNotPending($query)
    {
        return $query->where('is_pending', 0);
    }

    public function details()
    {
        return $this->proformaInvoiceDetails;
    }

    public function convert()
    {
        $this->converted_by = auth()->id();

        $this->is_pending = 0;

        $this->save();
    }

    public function cancel()
    {
        $this->converted_by = null;

        $this->is_pending = 0;

        $this->save();
    }

    public function isConverted()
    {
        return !$this->is_pending && $this->converted_by;
    }

    public function isPending()
    {
        return $this->is_pending && !$this->converted_by;
    }

    public function isCancelled()
    {
        return !$this->is_pending && !$this->converted_by;
    }
}
