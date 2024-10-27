<?php

namespace App\Dto;

class ForecastDto
{
    public string $datetimeText;
    public float $temperature;
    public float $temperatureFeelsLike;
    public float $windSpeed;
    public int $humidity;
    public int $pressure;
    public string $createdAt;

    public function toArray(): array
    {
        return [
            'datetime_text' => $this->datetimeText,
            'temperature' => $this->temperature,
            'temperature_feels_like' => $this->temperatureFeelsLike,
            'wind_speed' => $this->windSpeed,
            'humidity' => $this->humidity,
            'pressure' => $this->pressure,
            'created_at' => $this->createdAt
        ];
    }
}
