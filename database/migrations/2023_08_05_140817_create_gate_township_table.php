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
        Schema::create('gate_township', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('gate_id');
            $table->foreign('gate_id')->references('id')->on('gates');
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
        Schema::dropIfExists('gate_township');
    }
};
