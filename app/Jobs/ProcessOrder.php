<?php

namespace App\Jobs;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemWarehouse;
use App\Models\Product;
use App\Models\WarehouseStock;
use App\Services\StockCacheService;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ProcessOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;
    protected $items; 

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, array $items)
    {
        $this->order = $order;
        $this->items = $items;
    }

    /**
     * Execute the job.
     *
     * @return void
     */

    public function handle()
    {
        DB::beginTransaction();
        try {
            $totalAmount = 0;
            $stockCacheService = app(StockCacheService::class);

                foreach ($this->items as $item) {
                    $product = Product::findOrFail($item['product_id']);
                    $requiredQuantity = $item['quantity'];
                    $allocatedWarehouses = [];

                    //  Lock stock rows to prevent race conditions
                    $warehouses = WarehouseStock::where('product_id', $product->id)
                        ->where('quantity', '>', 0)
                        ->orderByDesc('quantity')
                        ->lockForUpdate()
                        ->get();

                    $warehouseSumQty = $warehouses->sum('quantity');

                    if ($warehouseSumQty < $requiredQuantity) {
                        throw new Exception(" Insufficient stock for {$product->name}. Required: {$requiredQuantity}, Available: {$warehouseSumQty}");
                    }

                    foreach ($warehouses as $warehouseStock) {
                        if ($requiredQuantity <= 0) break;

                        $allocatedQty = min($requiredQuantity, $warehouseStock->quantity);
                        $allocatedWarehouses[] = [
                            'warehouse_id' => $warehouseStock->warehouse_id,
                            'allocated_quantity' => $allocatedQty,
                        ];

                        // Deduct stock in DB (ensures real-time updates)
                        $warehouseStock->decrement('quantity', $allocatedQty);

                        // Update stock in cache
                        $stockCacheService->decrementStock($warehouseStock->warehouse_id, $product->id, $allocatedQty);

                        $requiredQuantity -= $allocatedQty;
                    }

                    foreach ($allocatedWarehouses as $alloc) {
                        $orderItemId = OrderItem::where('order_id', $this->order->id)
                            ->where('product_id', $item['product_id'])
                            ->value('id');

                        OrderItemWarehouse::create([
                            'order_item_id' => $orderItemId,
                            'warehouse_id' => $alloc['warehouse_id'],
                            'allocated_quantity' => $alloc['allocated_quantity'],
                        ]);

                    }

                    $totalAmount += $product->price * $item['quantity'];
                }

                // Update order status
                $this->order->update(['total_amount' => $totalAmount, 'status' => 'completed']);

                DB::commit();
            } catch (Exception $e) {
                Log::error("Order Processing Failed: " . $e->getMessage());
                DB::rollBack();
                $this->order->update(['status' => 'failed']);
            }
    }


}
