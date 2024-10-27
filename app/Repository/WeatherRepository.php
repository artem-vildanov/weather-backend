<?php

namespace App\Repository;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Dto\ForecastDto;
use Illuminate\Support\Facades\Log;

class WeatherRepository
{
    public string $tableName = 'weather_forecasts';

    public function getWeatherData(): array
    {
        return DB::table($this->tableName)->get()->all();
    }

    public function updateCurrentWeatherData(ForecastDto $forecastsGroup): void
    {
        $r = DB::table($this->tableName)
            ->where('id', 1)
            ->update($forecastsGroup->toArray());

        if ($r === 0) {
            $forecastArray = $forecastsGroup->toArray();
            $forecastArray['id'] = 1;
            DB::table($this->tableName)->insert($forecastArray);
        }
    }

    /** @param ForecastDto[] $forecastsGroup */
    public function updateFutureWeather(array $forecastsGroup): void
    {
        $id = 2;
        foreach($forecastsGroup as $forecastDto) {
            $r = DB::table($this->tableName)
                ->where('id', $id)
                ->update($forecastDto->toArray());

            if ($r === 0) {
                $forecastArray = $forecastDto->toArray();
                $forecastArray['id'] = $id;
                DB::table($this->tableName)->insert($forecastArray);
            }

            $id++;
        }
    }
}
