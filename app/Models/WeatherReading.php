<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Interfaces\Services\WeatherIconServiceInterface;

class WeatherReading extends Model
{
    use HasFactory;

    protected $fillable = [
        'weather_monitor_id',
        'city',
        'temperature',
        'feels_like',
        'weather_description',
        'wind_speed',
        'wind_direction',
        'chance_of_rain',
        'weather_code',
        'recorded_at',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
    ];

    public function weatherMonitor() : BelongsTo
    {
        return $this->belongsTo(WeatherMonitor::class);
    }

    public function getIconAttribute(): string
    {
        /** @var WeatherIconServiceInterface $service */
        $service = app(WeatherIconServiceInterface::class);
        return $service->getIcon($this->weather_code);
    }
}
