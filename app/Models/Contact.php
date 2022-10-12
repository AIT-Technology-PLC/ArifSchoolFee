<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contact extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function gdns()
    {
        return $this->hasMany(Gdn::class);
    }

    public function proformaInvoices()
    {
        return $this->hasMany(ProformaInvoice::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function Expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
