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

    public function definition()
    {
        return [
            'city' => $this->faker->city(),
            'country' => $this->faker->country(),
            'latitude' => $this->faker->latitude(),
            'longitude' => $this->faker->longitude(),
            'interval_minutes' => $this->faker->numberBetween(self::INTERVAL_MINUTES_MIN, self::INTERVAL_MINUTES_MAX),
            'next_run_at' => Carbon::now()->addMinutes($this->faker->numberBetween(self::INTERVAL_MINUTES_MIN, self::INTERVAL_MINUTES_MAX)),
        ];
    }
}
