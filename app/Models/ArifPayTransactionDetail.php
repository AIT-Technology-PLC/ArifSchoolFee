<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArifPayTransactionDetail extends Model
{
    use HasFactory, MultiTenancy, HasUserstamps, SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $fillable = [
        'transaction_status',
        'session_id_number',
        'notification_url',
        'uuid',
        'nonce',
        'phone',
        'total_amount',
        'transaction_id',
    ];

    public function gdnDetail()
    {
        return $this->belongsTo(GdnDetail::class);
    }
}
