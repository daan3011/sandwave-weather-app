<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherReadingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'city'                => $this->city,
            'temperature'         => $this->temperature,
            'feels_like'          => $this->feels_like,
            'weather_description' => $this->weather_description,
            'wind_speed'          => $this->wind_speed,
            'wind_direction'      => $this->wind_direction,
            'chance_of_rain'      => $this->chance_of_rain,
            'icon'                => $this->icon,
            'recorded_at'         => $this->recorded_at->format('d-m-Y H:i'),
        ];
    }
}
