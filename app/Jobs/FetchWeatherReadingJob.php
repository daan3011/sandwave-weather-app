<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Interfaces\Services\OpenWeatherMapServiceInterface;
use App\Interfaces\Services\WeatherMonitorServiceInterface;
use App\Interfaces\Services\WeatherReadingServiceInterface;

class FetchWeatherReadingJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $monitorId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $monitorId)
    {
        $this->monitorId = $monitorId;
    }

    /**
     * Execute the job.
     */
    public function handle(
        OpenWeatherMapServiceInterface $weatherService,
        WeatherMonitorServiceInterface $weatherMonitorService,
        WeatherReadingServiceInterface $weatherReadingService
    ) {
        $monitor = $weatherMonitorService->getWeatherMonitor($this->monitorId);

        if (!$monitor) {
            Log::warning("Monitor with ID {$this->monitorId} not found.");
            return;
        }

        try {
            $weatherData = $weatherService->getCurrentWeather($monitor->city);
            $processedData = $weatherService->extractWeatherData($weatherData);

            $weatherReadingService->createWeatherReading([
                'weather_monitor_id'  => $monitor->id,
                'city'                => $monitor->city,
                'temperature'         => $processedData['temperature'],
                'feels_like'          => $processedData['feels_like'],
                'weather_description' => $processedData['weather_description'],
                'wind_speed'          => $processedData['wind_speed'],
                'wind_direction'      => $processedData['wind_direction'],
                'chance_of_rain'      => $processedData['chance_of_rain'],
                'weather_code'        => $processedData['weather_code'],
                'recorded_at'         => $processedData['recorded_at'],
            ]);

            // Update next_run_at
            $monitor->next_run_at = now()->addMinutes($monitor->interval_minutes);
            $monitor->save();

            Log::info("Weather data fetched for {$monitor->city}.");
        } catch (\Exception $e) {
            Log::error("Failed to fetch weather data for {$monitor->city}: {$e->getMessage()}");
        }
    }
}
