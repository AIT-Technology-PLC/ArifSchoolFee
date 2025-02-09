<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecipientType extends Model
{
    use HasFactory;

    protected $fillable = ['type'];

    public function notice()
    {
        return $this->belongsTo(Notice::class);
    }
}
