<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\MultiTenancy;
use App\Traits\HasUserstamps;

class UserLog extends Model
{
    use HasFactory, MultiTenancy, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at', 'last_online_at'];

    protected $casts = [
        'last_online_at' => 'datetime',
    ];

    protected $fillable = [
        'company_id',
        'created_by',
        'updated_by',
        'name',
        'email',
        'phone',
        'role',
        'ip_address',
        'last_online_at',
    ];
}
