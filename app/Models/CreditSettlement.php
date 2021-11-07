<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CreditSettlement extends Model
{
    use SoftDeletes, HasFactory;

    protected $casts = [
        'settled_at' => 'datetime',
    ];

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function credit()
    {
        return $this->belongsTo(Credit::class);
    }
}
