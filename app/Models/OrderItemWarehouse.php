<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemWarehouse extends Model
{
    use HasFactory;

    protected $fillable = ['order_item_id', 'warehouse_id', 'allocated_quantity'];

    public function orderItem()
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

}
