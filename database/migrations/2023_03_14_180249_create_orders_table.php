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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code');
            $table->string('customer_name');
            $table->string('customer_phone_number');
            $table->unsignedBigInteger('township_id');
            $table->foreign('township_id')->references('id')->on('townships');
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->integer('quantity');
            $table->decimal('total_amount');
            $table->decimal('delivery_fees');
            $table->decimal('markup_delivery_fees');
            $table->text('remark');
            $table->string('status');
            $table->string('item_type');
            $table->string('full_address')->nullable();
            $table->timestamp('schedule_date')->nullable();
            $table->string('type');
            $table->string('collection_method');
            $table->string('proof_of_payment');            
            $table->unsignedBigInteger('last_updated_by')->nullable();
            $table->foreign('last_updated_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
