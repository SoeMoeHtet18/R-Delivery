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
        Schema::create('notifiables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('notification_id');
            $table->foreign('notification_id')->references('id')->on('notifications');
            $table->unsignedBigInteger('notifiable_id');
            $table->string('notifiable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifiables');
    }
};
