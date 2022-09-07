<?php

namespace App\Models;

use App\Traits\Branchable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChassisNumber extends Model
{
    use Branchable, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'is_document_received' => 'boolean',
        'is_added' => 'boolean',
        'is_subtracted' => 'boolean',
    ];

    public function jobDetail()
    {
        return $this->belongsTo(JobDetail::class);
    }

    public function gdnDetail()
    {
        return $this->belongsTo(GdnDetail::class);
    }

    public function grnDetail()
    {
        return $this->belongsTo(GrnDetail::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function scopeNotSold($query)
    {
        return $query->whereNull('gdn_detail_id');
    }
}
