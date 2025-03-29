<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id'); // Foreign key to customers table
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->string('status')->default('pending'); // e.g., pending, completed, etc.
            $table->timestamps();

            $table->foreign('customer_id')
                  ->references('id')->on('customers')
                  ->onDelete('cascade'); // Delete orders if the customer is deleted
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
