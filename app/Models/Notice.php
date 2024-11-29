<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Notice extends Model
{
    use HasFactory, MultiTenancy, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Notice');
    }

    public function warehouses()
    {
        return $this->belongsToMany(Warehouse::class);
    }

    public function recipientTypes()
    {
        return $this->hasMany(RecipientType::class);
    }
}
