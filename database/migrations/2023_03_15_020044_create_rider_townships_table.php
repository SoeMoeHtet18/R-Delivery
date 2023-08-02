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
        Schema::create('rider_township', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rider_id');
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->unsignedBigInteger('township_id');
            $table->foreign('township_id')->references('id')->on('townships');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rider_township');
    }
};
