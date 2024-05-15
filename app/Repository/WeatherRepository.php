<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Dto\ForecastDto;

class WeatherRepository
{
    public string $tableName = 'weather_forecasts';

    /**
     * @param ForecastDto[] $forecastsGroup
     */
    public function insertWeatherData(array $forecastsGroup): void
    {
        foreach($forecastsGroup as $forecastDto) {
            DB::table($this->tableName)->insert((array)$forecastDto);
        }
    }

    public function deleteWeatherData(): void
    {
        DB::table($this->tableName)->delete();
    }

    public function getWeatherData(): array
    {
        return DB::table($this->tableName)->get()->all();
    }
}