<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseCache extends Model
{
    use HasFactory;

    protected $fillable = ['warehouse_id', 'product_id', 'cached_quantity'];

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }
}
