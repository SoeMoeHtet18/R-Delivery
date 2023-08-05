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
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('pay_later')->nullable();
            $table->unsignedBigInteger('collection_group_id')->nullable();
            $table->foreign('collection_group_id')->references('id')->on('collection_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('pay_later');
            $table->dropForeign(['collection_group_id']);
            $table->dropColumn('collection_group_id');
        });
    }
};
