<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Message extends Model
{
    use HasFactory, MultiTenancy, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Email/SMS');
    }

    public function messageDetails()
    {
        return $this->hasMany(MessageDetail::class);
    }

    public function details()
    {
        return $this->messageDetails;
    }

    public function scopeSMS($query)
    {
        return $query->where('type', 'sms');
    }

    public function scopeEmail($query)
    {
        return $query->where('type', 'email');
    }

    public function scopeBoth($query)
    {
        return $query->where('type', 'both');
    }

    public function hasReceivedBy()
    {
        if (is_null($this->details()->first()->received_by)) {
            return false;
        }

        return true;
    }

    public function hasEmployee()
    {
        if (is_null($this->details()->first()->employee_id)) {
            return false;
        }

        return true;
    }

    public function hasStudent()
    {
        if (is_null($this->details()->first()->student_id)) {
            return false;
        }

        return true;
    }

    public function hasStaff()
    {
        if (is_null($this->details()->first()->staff_id)) {
            return false;
        }

        return true;
    }
}
