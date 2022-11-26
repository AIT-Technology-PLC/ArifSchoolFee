<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MerchandiseBatch extends Model
{
    use SoftDeletes;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'expiry_date' => 'date',
    ];

    public function merchandise()
    {
        return $this->belongsTo(Merchandise::class);
    }

    public function convert()
    {
        $this->is_converted_to_damage = 1;

        $this->save();
    }
}