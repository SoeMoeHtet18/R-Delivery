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
        Schema::table('townships', function (Blueprint $table) {
            $table->unsignedBigInteger('associable_id')->nullable();
            $table->string('associable_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('townships', function (Blueprint $table) {
            $table->dropColumn('associable_id');
            $table->dropColumn('associable_type');
        });
    }
};
