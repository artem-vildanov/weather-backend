<?php

namespace App\Dto;

class ForecastDto
{
    public string $datetime_text;
    public float $temperature;
    public float $temperature_feels_like;
    public float $wind_speed;
    public int $humidity;
    public int $pressure;
    public string $created_at;
}
