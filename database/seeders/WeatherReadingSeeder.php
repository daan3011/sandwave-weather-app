<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeatherMonitor;
use App\Models\WeatherReading;

class WeatherReadingSeeder extends Seeder
{
    const WEATHER_READINGS_PER_MONITOR = 24;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        WeatherMonitor::all()->each(function ($weatherMonitor) {
            WeatherReading::factory(self::WEATHER_READINGS_PER_MONITOR)->create([
                'weather_monitor_id' => $weatherMonitor->id,
                'city' => $weatherMonitor->city,
            ]);
        });
    }
}
