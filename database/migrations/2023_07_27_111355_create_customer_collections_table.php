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
            $table->unsignedBigInteger('collection_group_id')->nullable();
            $table->foreign('collection_group_id')->references('id')->on('collection_groups');
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');
            $table->text('items')->nullable();
            $table->decimal('paid_amount',10,2)->nullable(); 
            $table->boolean('is_way_fees_payable')->nullable();
            $table->string('item_image')->nullable();
            $table->string('status');
            $table->text('note')->nullable();
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
