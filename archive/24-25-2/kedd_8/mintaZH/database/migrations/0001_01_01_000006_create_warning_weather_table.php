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
        Schema::create('warning_weather', function (Blueprint $table) {
            $table->unsignedBigInteger('warning_id')->constrained('warnings')->onDelete('cascade');
            $table->unsignedBigInteger('weather_id')->constrained('weather')->onDelete('cascade');
            $table->primary(['warning_id', 'weather_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warning_weather');
    }
};
