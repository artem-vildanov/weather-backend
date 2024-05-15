<?php

namespace App\Dto;

use Carbon\Carbon;

class ThreeHoursForecastDto
{
    public int $datetime_unix;
    public string $datetime_text;
    public float $temperature;
    public float $temperature_feels_like;
    public float $wind_speed;
    public int $humidity;
    public int $pressure;
    public Carbon $created_at;
}