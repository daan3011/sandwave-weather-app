<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\WeatherMonitor;

class WeatherMonitorSeeder extends Seeder
{
    const DEFAULT_INTERVAL = 5;
    const SAMPLE_DATA_PATH = 'sample-data/cities.json';
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // avoid unique constraint violation by removing randmoness from using factory directly
        $cities = json_decode(file_get_contents(database_path(self::SAMPLE_DATA_PATH)), true);

        foreach ($cities as $cityData) {
            WeatherMonitor::updateOrCreate(
                ['city' => $cityData['city']],
                [
                    'country' => $cityData['country'],
                    'latitude' => $cityData['latitude'],
                    'longitude' => $cityData['longitude'],
                    'interval_minutes' => self::DEFAULT_INTERVAL,
                    'next_run_at' => now()->addMinutes(self::DEFAULT_INTERVAL),
                ]
            );
        }
    }
}
