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
        Schema::table('customer_collections', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->nullable()->change();
            $table->string('customer_name');
            $table->string('customer_phone_number');
            $table->unsignedBigInteger('shop_id');
            $table->foreign('shop_id')->references('id')->on('shops');
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id')->references('id')->on('riders');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_collections', function (Blueprint $table) {
            $table->unsignedBigInteger('order_id')->change();
            $table->dropColumn('customer_name');
            $table->dropColumn('customer_phone_number');
            $table->dropForeign(['shop_id']);
            $table->dropColumn('shop_id');
            $table->dropForeign(['rider_id']);
            $table->dropColumn('rider_id');
        });
    }
};
