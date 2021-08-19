<?php

namespace App\Models;

use App\Traits\Discountable;
use App\Traits\PricingTicket;
use App\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ProformaInvoice extends Model
{
    use HasFactory, SoftDeletes, PricingTicket, Discountable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
        'expires_on' => 'datetime',
        'is_pending' => 'boolean',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function convertedBy()
    {
        return $this->belongsTo(User::class, 'converted_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function proformaInvoiceDetails()
    {
        return $this->hasMany(ProformaInvoiceDetail::class);
    }

    public function scopeCompanyProformaInvoices($query)
    {
        return $query->where('company_id', userCompany()->id);
    }

    public function getCodeAttribute($value)
    {
        return Str::after($value, userCompany()->id . '_');
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
        return !$this->is_pending && $this->convertedBy;
    }

    public function isPending()
    {
        return $this->is_pending && !$this->convertedBy;
    }

    public function isCancelled()
    {
        return !$this->is_pending && !$this->convertedBy;
    }
}
