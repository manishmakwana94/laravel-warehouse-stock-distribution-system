<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['sku', 'name', 'description', 'price'];

    public function warehouseStocks()
    {
        return $this->hasMany(WarehouseStock::class, 'product_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

