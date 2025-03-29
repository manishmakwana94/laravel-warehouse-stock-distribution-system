<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class WarehouseUser extends Authenticatable
{
    use Notifiable;

    protected $guard = 'warehouse';

    protected $fillable = ['name', 'email', 'password', 'phone'];

    protected $hidden = ['password', 'remember_token'];

    public function warehouses()
    {
        return $this->hasMany(Warehouse::class, 'manager_id');
    }
}
