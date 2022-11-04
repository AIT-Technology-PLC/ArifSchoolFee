<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobDetailHistory extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function jobDetail()
    {
        return $this->belongsTo(JobDetail::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}