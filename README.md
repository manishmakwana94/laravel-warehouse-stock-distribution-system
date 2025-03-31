# Laravel 8 Stock Distribution System

### 🛠 Task: Optimized Warehouse Stock Distribution (Without Redis)


📌 Overview
- The task is to develop an efficient stock distribution system for a warehouse network. The system should allocate stock based on availability, minimize order splits, and optimize performance without using Redis.

🎯 Objectives
- Allocate stock optimally from multiple warehouses.
- Prioritize a single warehouse when possible to minimize shipping costs.
- Efficiently handle bulk orders and distribute stock when a single warehouse lacks sufficient inventory.
- Use database caching and queue processing instead of Redis.
- Implement a low-stock alert system for warehouse managers.

🛠️ Requirements
   * Stock Allocation Logic
    - When an order is placed, check stock across all warehouses.
    - If a single warehouse has enough stock, fulfill the order from there.
    - If stock is insufficient, split the order across multiple warehouses (minimizing the number of splits).
    - Ensure real-time stock locking to prevent overbooking during concurrent orders.
   * Efficient Stock Caching (Without Redis)
    - Implement MySQL-based caching instead of Redis.
    - Store precomputed stock summaries in a warehouse_cache table.
    - Use batch updates to refresh stock data periodically.
   * Queue-Based Order Processing
    - Use Laravel’s Database Queue Driver to process stock allocation asynchronously.
    - Ensure that high-volume orders are handled efficiently without slowing down the system.
   * Bulk Query Optimization
    - Fetch stock data for all ordered products in a single query.
    - Perform stock updates in bulk to reduce query load.
   * Warehouse Stock Alert System
    - Implement a scheduled task that checks for low-stock products.
    - Send email or SMS alerts to warehouse managers when stock drops below a threshold.


📌 Deliverables
- A Laravel-based version 8 solution implementing the stock distribution logic.
- SQL queries for optimized stock lookups and caching.
- Laravel queue implementation for handling stock allocation.
- A low-stock alert system that notifies warehouse managers.
- Well-commented and structured code with a README file explaining implementation details.


## 📌 Project Overview
The **Stock Distribution System** is designed for warehouse networks to efficiently manage stock allocation, order processing, and inventory tracking. This system optimizes stock distribution by minimizing order splits, ensuring real-time stock locking, and processing orders asynchronously using queues.

### 🔹 Key Features:
- **Stock Allocation Logic**: Smart allocation from single or multiple warehouses.
- **Efficient Stock Caching (Without Redis)**: Uses a `warehouse_cache` table for fast lookups.
- **Queue-Based Order Processing**: Laravel queues for handling orders asynchronously.
- **Bulk Query Optimization**: Optimized MySQL queries for stock updates.
- **Warehouse Stock Alert System**: Notifies managers of low stock via email/SMS.

## 🚀 Installation & Setup

### ✅ Prerequisites:
- PHP 7.4+
- Laravel 8
- MySQL 5.7+
- Composer
- Supervisor (for queue workers)

### 🔧 Installation Steps:
1. **Clone the repository:**
   ```sh
   git clone https://github.com/your-repo/laravel-stock-distribution.git
   cd laravel-stock-distribution
   ```
2. **Install dependencies:**
   ```sh
   composer install
   ```
3. **Create & configure `.env` file:**
   ```sh
   cp .env.example .env
   ```
   - Set database credentials (`DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`)
   - Configure queue driver (`QUEUE_CONNECTION=database`)
   - Configure mail settings for alerts (`MAIL_MAILER`, `MAIL_HOST`, etc.)
4. **Run database migrations & seeders:**
   ```sh
   php artisan migrate --seed
   ```
5. **Start the Laravel queue worker:**
   ```sh
   php artisan queue:work --daemon
   ```
6. **Run the application:**
   ```sh
   php artisan serve
   ```

## 🔹 Key Functionalities with Code Explanation

### 1️⃣ Stock Allocation Logic
- When an order is placed:
  - Check stock availability across all warehouses.
  - If a single warehouse has enough stock, allocate from there.
  - Otherwise, split allocation across multiple warehouses.
  - Ensure real-time stock locking to prevent overbooking.

```php
public function allocateStock(Order $order)
{
    $products = $order->products;
    foreach ($products as $product) {
        $warehouses = Warehouse::where('stock', '>=', $product->quantity)
                               ->orderBy('priority', 'asc')
                               ->get();
        if ($warehouses->isNotEmpty()) {
            $this->assignStock($warehouses->first(), $product);
        } else {
            $this->splitStock($product);
        }
    }
}
```

### 2️⃣ Efficient Stock Caching (Without Redis)
- Uses `warehouse_cache` table for quick stock lookups.
- Data is refreshed in batch operations to avoid performance bottlenecks.

```php
public function updateStockCache()
{
    DB::table('warehouse_cache')->truncate();
    DB::table('warehouse_cache')->insert(
        Warehouse::selectRaw('id, SUM(stock) as total_stock')
        ->groupBy('id')
        ->get()
        ->toArray()
    );
}
```

### 3️⃣ Queue-Based Order Processing
- Orders are processed asynchronously using Laravel's queue system.

```php
Order::create($data);
ProcessOrder::dispatch($order);
```

```php
class ProcessOrder implements ShouldQueue
{
    public function handle(Order $order)
    {
        // Stock allocation logic
    }
}
```

### 4️⃣ Bulk Query Optimization
- Fetches stock for all ordered products in one query.
- Updates stock in bulk.

```php
$stockData = Product::whereIn('id', $productIds)
    ->select('id', 'stock')
    ->get();

DB::table('products')->whereIn('id', $productIds)
    ->update(['stock' => DB::raw('stock - ordered_quantity')]);
```

### 5️⃣ Warehouse Stock Alert System
- Checks for low-stock products and sends notifications.

```php
$lowStockProducts = Product::where('stock', '<', 10)->get();
foreach ($lowStockProducts as $product) {
    Notification::send($warehouseManager, new LowStockAlert($product));
}
```

## 📖 Usage Guide

### 🔹 API Routes
#### Place an Order
```http
POST /api/orders
```
**Request:**
```json
{
    "products": [
        { "id": 1, "quantity": 5 },
        { "id": 2, "quantity": 3 }
    ]
}
```
**Response:**
```json
{
    "status": "success",
    "message": "Order placed successfully."
}
```

### 🔹 Run Queue Worker
```sh
php artisan queue:work
```

## 📌 Deployment Guide
1. **Setup Laravel on a production server**
2. **Configure environment variables (`.env`)**
3. **Set up Supervisor for queue processing:**
   ```sh
   sudo nano /etc/supervisor/conf.d/laravel-worker.conf
   ```
   Add:
   ```
   [program:laravel-worker]
   command=php artisan queue:work --daemon
   autostart=true
   autorestart=true
   user=www-data
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/var/log/worker.log
   ```
   Restart Supervisor:
   ```sh
   sudo supervisorctl reload
   ```

## 🛠 Troubleshooting & Logs

### 🔹 Common Issues & Fixes
- **Queues not working?** Run: `php artisan queue:restart`
- **Database issues?** Check `.env` and run: `php artisan migrate --seed`

### 🔹 Log Monitoring
View logs:
```sh
cat storage/logs/laravel.log
```

---

## 🎯 Task Summary: Optimized Warehouse Stock Distribution (Without Redis)

### 📌 Objectives
- Allocate stock optimally from multiple warehouses.
- Prioritize a single warehouse to minimize shipping costs.
- Efficiently handle bulk orders.
- Use MySQL-based caching (no Redis).
- Implement a low-stock alert system.

### 🛠 Deliverables
- Laravel-based stock distribution logic.
- Optimized SQL queries for stock management.
- Queue processing for stock allocation.
- Well-documented and structured code.

---
This README provides a complete guide for understanding, installing, and using the Laravel 8-based Stock Distribution System.

### Migration and models 




# 1. Create the Customers table
php artisan make:migration create_customers_table --create=customers

# 2. Create the Warehouse Users table (for multi‑auth warehouse managers/staff)
php artisan make:migration create_warehouse_users_table --create=warehouse_users

# 3. Create the Warehouses table
php artisan make:migration create_warehouses_table --create=warehouses

# 4. Create the Products table
php artisan make:migration create_products_table --create=products

# 5. Create the Warehouse Stock table (pivot table for warehouse and products)
php artisan make:migration create_warehouse_stock_table --create=warehouse_stock

# 6. Create the Orders table
php artisan make:migration create_orders_table --create=orders

# 7. Create the Order Items table
php artisan make:migration create_order_items_table --create=order_items

# 8. Create the Warehouse Cache table (for precomputed stock summaries)
php artisan make:migration create_warehouse_cache_table --create=warehouse_cache

# 9. Create the Jobs table for Laravel’s Database Queue Driver
php artisan queue:table


------------------------------------------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->timestamps(); // created_at and updated_at
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
```
-------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseUsersTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_users', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password'); // Encrypted password
            $table->string('phone')->nullable();
            $table->rememberToken(); // For "remember me" functionality
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_users');
    }
}

```
----------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehousesTable extends Migration
{
    public function up()
    {
        Schema::create('warehouses', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name');
            $table->string('location');
            $table->string('contact_number')->nullable();
            // Foreign key linking to the warehouse_users table (manager)
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();

            $table->foreign('manager_id')
                  ->references('id')->on('warehouse_users')
                  ->onDelete('set null'); // If manager is removed, set to null
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouses');
    }
}
```

----------------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('sku')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
```
------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseStockTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_stock', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('warehouse_id'); // Foreign key to warehouses
            $table->unsignedBigInteger('product_id');   // Foreign key to products
            $table->integer('quantity')->default(0);
            $table->timestamps();

            // Prevent duplicate entries for the same warehouse and product
            $table->unique(['warehouse_id', 'product_id']);

            // Define foreign key relationships
            $table->foreign('warehouse_id')
                  ->references('id')->on('warehouses')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_stock');
    }
}
```
----------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('customer_id'); // Foreign key to customers table
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('pending'); // e.g., pending, completed, etc.
            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')->on('customers')
                  ->onDelete('cascade'); // Delete orders if the customer is deleted
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
```

----------------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('order_id');     // Foreign key to orders table
            $table->unsignedBigInteger('product_id');     // Foreign key to products table
            $table->unsignedBigInteger('warehouse_id')->nullable(); // Optional: warehouse fulfilling this item
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->timestamps();

            // Define foreign key relationships
            $table->foreign('order_id')
                  ->references('id')->on('orders')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');

            $table->foreign('warehouse_id')
                  ->references('id')->on('warehouses')
                  ->onDelete('set null'); // If warehouse is removed, leave warehouse_id null
        });
    }

    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
```
--------------------------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseCacheTable extends Migration
{
    public function up()
    {
        Schema::create('warehouse_cache', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->unsignedBigInteger('warehouse_id'); // Foreign key to warehouses
            $table->unsignedBigInteger('product_id');     // Foreign key to products
            $table->integer('cached_quantity')->default(0);
            $table->timestamp('last_updated')->nullable();
            $table->timestamps();

            // Ensure unique records per warehouse–product combination
            $table->unique(['warehouse_id', 'product_id']);

            // Define foreign key relationships
            $table->foreign('warehouse_id')
                  ->references('id')->on('warehouses')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_cache');
    }
}

```
---------------------------
 ```sh
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobsTable extends Migration
{
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id'); // Primary key
            $table->string('queue')->index(); // Name of the queue
            $table->longText('payload'); // Serialized job data
            $table->unsignedTinyInteger('attempts')->default(0); // Number of attempts
            $table->unsignedInteger('reserved_at')->nullable(); // When the job was reserved
            $table->unsignedInteger('available_at'); // When the job is available for processing
            $table->unsignedInteger('created_at'); // Job creation timestamp
        });
    }

    public function down()
    {
        Schema::dropIfExists('jobs');
    }
}
 ```

