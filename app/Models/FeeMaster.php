<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class FeeMaster extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps, LogsActivity;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
        ->useLogName('Fee Master');
    }

    public function feeType()
    {
        return $this->belongsTo(FeeType::class);
    }
}
