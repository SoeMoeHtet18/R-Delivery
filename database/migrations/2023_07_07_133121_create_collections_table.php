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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->integer('total_quantity')->nullable();
            $table->decimal('total_amount', 12, 2)->nullable();
            $table->decimal('paid_amount', 12, 2)->nullable();
            $table->unsignedBigInteger('collection_group_id')->nullable();
            $table->foreign('collection_group_id')->references('id')->on('collection_groups');
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('collected_at')->nullable();
            $table->text('note')->nullable();
            $table->string('status');
            $table->boolean('is_payable');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
