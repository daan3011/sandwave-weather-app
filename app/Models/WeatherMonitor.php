<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WeatherMonitor extends Model
{
    protected $fillable = [
        'city',
        'country',
        'latitude',
        'longitude',
        'interval_minutes',
        'next_run_at',
    ];

    public function weatherReadings() : HasMany
    {
        return $this->hasMany(WeatherReading::class);
    }
}
