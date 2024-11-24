<?php

namespace Database\Factories;

use Carbon\Carbon;
use App\Models\WeatherMonitor;
use Illuminate\Database\Eloquent\Factories\Factory;

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
    const DAY_MINUTES = 1440;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $weatherMonitor = WeatherMonitor::query()->inRandomOrder()->value('id');
        $city = WeatherMonitor::find($weatherMonitor)?->city ?? 'Unknown';

        $weatherConfigs = config('weather_icons', []);
        $selectedWeather = $this->faker->randomElement($weatherConfigs);

        $weatherCode = $this->faker->randomElement($selectedWeather['codes'] ?? []);

        $descriptions = [
            ['min' => 200, 'max' => 232, 'description' => 'Thunderstorm'],
            ['min' => 300, 'max' => 321, 'description' => 'Light rain'],
            ['min' => 500, 'max' => 531, 'description' => 'Rain'],
            ['min' => 600, 'max' => 622, 'description' => 'Snow'],
            ['min' => 701, 'max' => 781, 'description' => 'Mist'],
            ['min' => 800, 'max' => 800, 'description' => 'Clear sky'],
            ['min' => 801, 'max' => 804, 'description' => 'Cloudy'],
        ];

        // Match weather code
        $weatherDescription = 'Unknown';
        foreach ($descriptions as $range) {
            if ($weatherCode >= $range['min'] && $weatherCode <= $range['max']) {
                $weatherDescription = $range['description'];
                break;
            }
        }

        return [
            'weather_monitor_id' => $weatherMonitor,
            'city' => $city,
            'temperature' => $this->faker->randomFloat(self::DECIMALS, self::TEMPERATURE_MIN, self::TEMPERATURE_MAX),
            'feels_like' => $this->faker->randomFloat(self::DECIMALS, self::FEELS_LIKE_MIN, self::FEELS_LIKE_MAX),
            'weather_description' => $weatherDescription,
            'wind_speed' => $this->faker->randomFloat(self::DECIMALS, self::WIND_SPEED_MIN, self::WIND_SPEED_MAX),
            'wind_direction' => $this->faker->numberBetween(self::WIND_DIRECTION_MIN, self::WIND_DIRECTION_MAX),
            'chance_of_rain' => $this->faker->numberBetween(self::CHANCE_OF_RAIN_MIN, self::CHANCE_OF_RAIN_MAX),
            'weather_code' => $weatherCode,
            'recorded_at' => Carbon::now()->subMinutes($this->faker->numberBetween(1, self::DAY_MINUTES)),
        ];
    }
}
