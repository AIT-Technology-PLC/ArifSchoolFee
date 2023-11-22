<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'starts_on' => 'date',
        'is_approved' => 'boolean',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class);
    }

    public function expiresOn(): Attribute
    {
        return Attribute::get(fn() => $this->starts_on->addMonths($this->months));
    }

    public function approve()
    {
        $this->is_approved = 1;

        if (is_null($this->company->subscription_expires_on) || $this->company->subscription_expires_on->isPast()) {
            $this->starts_on = today();
        }

        if (!is_null($this->company->subscription_expires_on) && !$this->company->subscription_expires_on->isPast()) {
            $this->starts_on = $this->company->subscription_expires_on->addDay();
        }

        $this->save();

        $this->company->subscribe($this);
    }

    public function isApproved()
    {
        return $this->is_approved;
    }
}
