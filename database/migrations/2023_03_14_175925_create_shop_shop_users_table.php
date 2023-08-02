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
        Schema::create('shop_shop_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_user_id');
            $table->foreign('shop_user_id')->references('id')->on('shop_users');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shop_shop_user');
    }
};
