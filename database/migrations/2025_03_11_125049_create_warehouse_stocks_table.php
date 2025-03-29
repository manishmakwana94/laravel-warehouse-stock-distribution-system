<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseStocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('warehouse_id'); // Foreign key to warehouses
            $table->unsignedBigInteger('product_id');   // Foreign key to products
            $table->integer('quantity')->default(0);
            $table->timestamps();
            // $table->unique(['warehouse_id', 'product_id']);
            $table->foreign('warehouse_id')
                  ->references('id')->on('warehouses')
                  ->onDelete('cascade');

            $table->foreign('product_id')
                  ->references('id')->on('products')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('warehouse_stocks');
    }
}
