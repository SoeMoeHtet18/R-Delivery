<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customer_collections', function (Blueprint $table) {
            $table->id();
            $table->string('customer_collection_code');
            $table->string('customer_name');
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->text('items')->nullable();
            $table->decimal('paid_amount',10,2)->nullable();
            $table->boolean('is_way_payable')->nullable();
            $table->string('item_image')->nullable();
            $table->string('quantity')->nullable();
            $table->string('status');
            $table->text('notes')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_collections');
    }
};
