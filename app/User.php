<?php

namespace App;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'position', 'enabled',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
        'enabled' => 'boolean',
    ];

    public function employee()
    {
        return $this->hasOne(Models\Employee::class, 'user_id');
    }

    public function employeesCreated()
    {
        return $this->hasMany(Models\Employee::class, 'created_by');
    }

    public function employeesUpdated()
    {
        return $this->hasMany(Models\Employee::class, 'updated_by');
    }
}
