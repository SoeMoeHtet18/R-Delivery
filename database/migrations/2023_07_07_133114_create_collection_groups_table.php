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
        Schema::create('collection_groups', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_amount', 12 , 2);
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->timestamp('assigned_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_groups');
    }
};
