<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChassisNumber extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_document_received' => 'boolean',
        'is_added' => 'boolean',
        'is_subtracted' => 'boolean',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class, 'job_order_id');
    }

    public function gdn()
    {
        return $this->belongsTo(Gdn::class);
    }

    public function grn()
    {
        return $this->belongsTo(Grn::class);
    }
}
