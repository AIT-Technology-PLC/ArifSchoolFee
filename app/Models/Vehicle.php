<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Vehicle extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Vehicle');
    }

    public function routes()
    {
        return $this->belongsToMany(Route::class);
    }
}
