<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\WeatherMonitor;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WeatherReading>
 */
class WeatherReadingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'weather_monitor_id' => WeatherMonitor::factory(),
            'city' => $this->faker->city,
            'temperature' => $this->faker->randomFloat(1, -30, 50),
            'feels_like' => $this->faker->randomFloat(1, -30, 50),
            'weather_description' => $this->faker->sentence(3),
            'wind_speed' => $this->faker->randomFloat(1, 0, 20),
            'wind_direction' => $this->faker->numberBetween(0, 360),
            'chance_of_rain' => $this->faker->numberBetween(0, 100),
            'recorded_at' => Carbon::now()->subHours($this->faker->numberBetween(1, 24)),
        ];
    }
}
