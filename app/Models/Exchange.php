<?php

namespace App\Models;

use App\Models\ExchangeDetail;
use App\Models\Returnn;
use App\Traits\Approvable;
use App\Traits\Branchable;
use App\Traits\HasCustomFields;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Traits\PricingTicket;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends Model
{
    use SoftDeletes, MultiTenancy, Branchable, Approvable, HasUserstamps, HasCustomFields, PricingTicket;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function exchangeDetails()
    {
        return $this->hasMany(ExchangeDetail::class);
    }

    public function exchangeable()
    {
        return $this->morphTo();
    }

    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by')->withDefault(['name' => 'N/A']);
    }

    public function returnn()
    {
        return $this->belongsTo(Returnn::class, 'return_id');
    }

    public function execute()
    {
        $this->executed_by = authUser()->id;

        $this->save();
    }

    public function isExecuted()
    {
        return $this->executed_by != null;
    }

    public function scopeExecuted($query)
    {
        return $query->whereNotNull('executed_by');
    }

    public function scopeNotExecuted($query)
    {
        return $query->whereNull('executed_by');
    }

    public function details()
    {
        return $this->exchangeDetails;
    }
}
