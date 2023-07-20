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
        Schema::create('deficits', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rider_id')->nullable();
            $table->foreign('rider_id')->references('id')->on('riders');
            $table->string('total_amount');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deficits');
    }
};
