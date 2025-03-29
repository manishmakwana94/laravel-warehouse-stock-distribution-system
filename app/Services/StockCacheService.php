<?php
namespace App\Services;

use App\Models\Warehouse;
use App\Models\WarehouseCache;
use App\Models\WarehouseStock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class StockCacheService
{
    /**
     * Update stock cache for all warehouses
     */
    public function updateStockCache()
    {
        DB::transaction(function () {
            // Clear existing cache
            WarehouseCache::truncate();
            
            $stockData = WarehouseStock::select(
                'warehouse_id',
                'product_id',
                DB::raw('SUM(quantity) as total_quantity')
            )
                ->groupBy('warehouse_id', 'product_id')
                ->get();


            foreach ($stockData as $stock) {
                WarehouseCache::create([
                    'warehouse_id' => $stock->warehouse_id,
                    'product_id' => $stock->product_id,
                    'cached_quantity' => $stock->total_quantity,
                ]);
            }
        });
    }

    /**
     * Get available stock from the cache
     */
    public function getStockFromCache($productId)
    {

        $getStockCache = WarehouseCache::where('product_id', $productId)
            ->where('cached_quantity', '>', 0)
            ->orderByDesc('cached_quantity')
            ->get();
            return $getStockCache;
    }

    public function decrementStock($warehouseId, $productId, $quantity)
    {
        $warehouseStock = WarehouseCache::where('warehouse_id', $warehouseId)
            ->where('product_id', $productId)
            ->first();

        if ($warehouseStock) {
            $newQuantity = $warehouseStock->cached_quantity - $quantity;

            if ($newQuantity >= 0) {
                $warehouseStock->update(['cached_quantity' => $newQuantity]);
                WarehouseStock::where('warehouse_id', $warehouseId)
                    ->where('product_id', $productId)
                    ->decrement('quantity', $quantity);
                return true;
            }
        }
        return false;
    }

}
