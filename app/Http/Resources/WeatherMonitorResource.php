<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class WeatherMonitorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'city'             => $this->city,
            'country'          => $this->country,
            'latitude'         => $this->latitude,
            'longitude'        => $this->longitude,
            'interval_minutes' => $this->interval_minutes,
            'next_run_at'      => $this->next_run_at->format('d-m-Y H:i'),
        ];
    }
}
