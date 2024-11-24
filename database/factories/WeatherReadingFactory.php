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
    const DECIMALS = 1;
    const TEMPERATURE_MIN = -10;
    const TEMPERATURE_MAX = 35;
    const FEELS_LIKE_MIN = -15;
    const FEELS_LIKE_MAX = 40;
    const WIND_SPEED_MIN = 0;
    const WIND_SPEED_MAX = 20;
    const WIND_DIRECTION_MIN = 0;
    const WIND_DIRECTION_MAX = 360;
    const CHANCE_OF_RAIN_MIN = 0;
    const CHANCE_OF_RAIN_MAX = 100;
    const WEATHER_CODE_MIN = 100;
    const WEATHER_CODE_MAX = 800;
    const DAY_MINUTES = 1440;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weatherMonitor = WeatherMonitor::inRandomOrder()->first();

        return [
            'weather_monitor_id' => $weatherMonitor->id,
            'city' => $weatherMonitor->city,
            'temperature' => $this->faker->randomFloat(self::DECIMALS, self::TEMPERATURE_MIN, self::TEMPERATURE_MAX),
            'feels_like' => $this->faker->randomFloat(self::DECIMALS, self::FEELS_LIKE_MIN, self::FEELS_LIKE_MAX),
            'weather_description' => $this->faker->randomElement([
                'Clear sky',
                'Partly cloudy',
                'Overcast',
                'Light rain',
                'Heavy rain',
                'Thunderstorm',
                'Snow',
                'Fog'
            ]),
            'wind_speed' => $this->faker->randomFloat(self::DECIMALS, 0, 20),
            'wind_direction' => $this->faker->numberBetween(self::WIND_DIRECTION_MIN, self::WIND_DIRECTION_MAX),
            'chance_of_rain' => $this->faker->numberBetween(self::CHANCE_OF_RAIN_MIN, self::CHANCE_OF_RAIN_MAX),
            'weather_code' => $this->faker->numberBetween(self::WEATHER_CODE_MIN, self::WEATHER_CODE_MAX),
            'recorded_at' => Carbon::now()->subMinutes($this->faker->numberBetween(1, self::DAY_MINUTES)),
        ];
    }
}
