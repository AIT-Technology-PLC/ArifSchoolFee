<?php

namespace App\Models;

use App\Traits\Branchable;
use App\Traits\Discountable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use App\User;
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

    public function getAll()
    {
        if (auth()->user()->hasRole('System Manager') || auth()->user()->hasRole('Analyst')) {
            return $this->latest()->get();
        }

        return $this->byBranch()->latest()->get();
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
