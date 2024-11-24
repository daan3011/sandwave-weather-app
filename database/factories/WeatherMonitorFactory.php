<?php
namespace Database\Factories;

use App\Models\WeatherMonitor;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class WeatherMonitorFactory extends Factory
{
    protected $model = WeatherMonitor::class;

    const INTERVAL_MINUTES_MIN = 30;
    const INTERVAL_MINUTES_MAX = 1440; // 24 hours

    protected array $cities;

    public function __construct(...$args)
    {
        parent::__construct(...$args);

        $this->cities = json_decode(file_get_contents(database_path('sample-data/cities.json')), true);
    }

    public function definition()
    {
        $cityData = $this->faker->randomElement($this->cities);

        return [
            'city' => $cityData['city'],
            'country' => $cityData['country'],
            'latitude' => $cityData['latitude'],
            'longitude' => $cityData['longitude'],
            'interval_minutes' => $this->faker->numberBetween(self::INTERVAL_MINUTES_MIN, self::INTERVAL_MINUTES_MAX),
            'next_run_at' => Carbon::now()->addMinutes($this->faker->numberBetween(self::INTERVAL_MINUTES_MIN, self::INTERVAL_MINUTES_MAX)),
        ];
    }
}
