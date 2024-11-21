<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('weather_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('weather_monitor_id')->constrained()->onDelete('cascade');
            $table->string('city');
            $table->float('temperature');
            $table->float('feels_like');
            $table->string('weather_description');
            $table->float('wind_speed');
            $table->integer('wind_direction');
            $table->float('chance_of_rain')->nullable();
            $table->timestamp('recorded_at');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weather_readings');
    }
};
