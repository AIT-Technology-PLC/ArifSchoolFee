<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Siv extends Model
{
    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function sivDetails()
    {
        return $this->hasMany(SivDetails::class);
    }
}
