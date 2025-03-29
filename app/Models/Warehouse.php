<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Warehouse extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'location',
        'contact_number',
        'manager_id',
    ];

    public function manager()
    {
        return $this->belongsTo(WarehouseUser::class, 'manager_id');
    }

    public function stocks()
    {
        return $this->hasMany(WarehouseStock::class, 'warehouse_id');
    }
}
