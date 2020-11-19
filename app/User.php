<?php

namespace App;

use App\Models\Employee;
use App\Models\Product;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'last_online_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->hasOne(Employee::class, 'user_id');
    }

    public function employeesCreated()
    {
        return $this->hasMany(Employee::class, 'created_by');
    }

    public function employeesUpdated()
    {
        return $this->hasMany(Employee::class, 'updated_by');
    }

    public function productsCreated()
    {
        return $this->hasMany(Product::class, 'created_by');
    }

    public function productsUpdated()
    {
        return $this->hasMany(Product::class, 'updated_by');
    }
}
