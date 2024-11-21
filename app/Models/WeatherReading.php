<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WeatherReading extends Model
{
    protected $fillable = [
        'weather_monitor_id',
        'city',
        'temperature',
        'feels_like',
        'weather_description',
        'wind_speed',
        'wind_direction',
        'chance_of_rain',
        'recorded_at',
    ];

    public function weatherMonitor() : BelongsTo
    {
        return $this->belongsTo(WeatherMonitor::class);
    }
}
