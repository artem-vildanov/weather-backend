<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Dto\ForecastDto;
use Illuminate\Support\Facades\Log;

class WeatherRepository
{
    public string $tableName = 'weather_forecasts';

    /**
     * @param ForecastDto[] $forecastsGroup
     */
//    public function insertWeatherData(array $forecastsGroup): void
//    {
//        foreach($forecastsGroup as $forecastDto) {
//            DB::table($this->tableName)->insert((array)$forecastDto);
//        }
//    }
//
//    public function deleteWeatherData(): void
//    {
//        DB::table($this->tableName)->delete();
//    }

    public function getWeatherData(): array
    {
        return DB::table($this->tableName)->get()->all();
    }

    public function updateCurrentWeatherData(array $forecastsGroup): void
    {
        DB::table($this->tableName)->where('id', 1)
            ->update($forecastsGroup);
    }

    public function updateFutureWeather(array $forecastsGroup): void
    {
        $id = 2;
        foreach($forecastsGroup as $forecastDto) {
            DB::table($this->tableName)->where('id', $id)
                ->update((array)$forecastDto);

            $id++;
        }
    }
}
