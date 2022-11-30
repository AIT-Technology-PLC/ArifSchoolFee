<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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

    public function isTypeAdded()
    {
        return $this->type == "added";
    }

    public function isTypeSubtracted()
    {
        return $this->type == "subtracted";
    }
}