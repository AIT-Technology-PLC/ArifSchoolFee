<?php

namespace App\Models;

use App\Traits\Branchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomFieldValue extends Model
{
    use HasFactory, SoftDeletes, Branchable;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    public function customField()
    {
        return $this->belongsTo(CustomField::class);
    }

    public function customFieldValuable()
    {
        return $this->morphTo();
    }
}
