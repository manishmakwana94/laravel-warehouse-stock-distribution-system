<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Warehouse;
use App\Models\WarehouseStock;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class WarehouseStockSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        $products = Product::pluck('id')->toArray();
        $warehouses = Warehouse::pluck('id')->toArray();

        $records = [];

        for ($i = 0; $i < 50; $i++) {
            $records[] = [
                'warehouse_id' => $faker->randomElement($warehouses),
                'product_id' => $faker->randomElement($products),
                'quantity' => $faker->numberBetween(1, 10), // Small random stock (1-10)
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

         WarehouseStock::insert($records);
    }
}
