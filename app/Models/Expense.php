<?php

namespace App\Models;

use App\Models\ExpenseDetail;
use App\Models\Supplier;
use App\Models\Tax;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use MultiTenancy, SoftDeletes, Approvable, PricingTicket, Branchable, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function expenseDetails()
    {
        return $this->hasMany(ExpenseDetail::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function contact()
    {
        return $this->belongsTo(Contact::class);
    }

    public function taxModel()
    {
        return $this->belongsTo(Tax::class, 'tax_id');
    }

    public function getTaxAttribute()
    {
        return number_format(
            $this->subtotalPrice * $this->localTaxRate,
            2,
            thousands_separator:''
        );
    }

    public function getLocalTaxRateAttribute()
    {
        return $this->taxModel->amount;
    }

    public function details()
    {
        return $this->expenseDetails;
    }
}