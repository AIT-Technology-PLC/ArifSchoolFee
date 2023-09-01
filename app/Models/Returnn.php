<?php

namespace App\Models;

use App\Traits\Addable;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Returnn extends Model
{
    use MultiTenancy, Branchable, HasFactory, SoftDeletes, Approvable, PricingTicket, HasUserstamps, Addable, HasCustomFields;

    protected $table = 'returns';

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'issued_on' => 'datetime',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function returnDetails()
    {
        return $this->hasMany(ReturnDetail::class, 'return_id');
    }

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function details()
    {
        return $this->returnDetails;
    }

    public function canAffectInventoryValuation()
    {
        return true;
    }
}