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
            $table->unsignedBigInteger('city_id')->nullable()->after('order_id');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->unsignedBigInteger('township_id')->nullable()->after('city_id');
            $table->foreign('township_id')->references('id')->on('townships');
            $table->text('address')->nullable()->after('township_id');
            $table->timestamp('schedule_date')->nullable()->after('status');
            $table->timestamp('pending_at')->nullable()->after('schedule_date');
            $table->timestamp('picking_at')->nullable()->after('pending_at');
            $table->timestamp('warehouse_at')->nullable()->after('picking_at');
            $table->timestamp('complete_at')->nullable()->after('warehouse_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customer_collections', function (Blueprint $table) {
            $table->dropForeign(['city_id']);
            $table->dropColumn('city_id');
            $table->dropForeign(['township_id']);
            $table->dropColumn('township_id');
            $table->dropColumn('address');
            $table->dropColumn('schedule_date');
            $table->dropColumn('pending_at');
            $table->dropColumn('warehouse_at');
            $table->dropColumn('complete_at');
            $table->dropColumn('picking_at');
        });
    }
};
