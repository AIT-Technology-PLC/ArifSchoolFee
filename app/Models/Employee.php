<?php

namespace App\Models;

use App\Traits\HasUserstamps;
use App\Traits\MultiTenancy;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use MultiTenancy, SoftDeletes, HasUserstamps;

    protected $guarded = ['id', 'created_at', 'updated_at', 'deleted_at'];

    protected $casts = [
        'enabled' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAll()
    {
        return $this->get();
    }

    public function countAllEmployees()
    {
        return $this->count();
    }

    public function countEnabledEmployees()
    {
        return $this->where('enabled', 1)->count();
    }

    public function countBlockedEmployees()
    {
        return $this->where('enabled', 0)->count();
    }
}
