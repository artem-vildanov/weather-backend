<?php

namespace App\Services;

use App\Dto\ThreeHoursForecastDto;
use App\Repository\WeatherRepository;
use stdClass;
use Exception;

class OpenWeatherApiService
{
    public function __construct(private readonly WeatherRepository $weatherRepository) {}

    public function updateWeatherData(): void
    {
        $data = $this->fetchDataFromOpenWeather();

        /**
         * @var stdClass[]
         */
        $forecastsObjectsGroup = $data->list;

        /**
         * @var ForecastDto[]
         */
        $forecastsDtoGroup = [];

        foreach($forecastsObjectsGroup as $forecast)
        {
            $forecastsDtoGroup[] = $this->mapForecast($forecast);
        }

        $this->weatherRepository->deleteWeatherData();
        $this->weatherRepository->insertWeatherData($forecastsDtoGroup);
    }

    private function fetchDataFromOpenWeather(): stdClass
    {
        $url = 'https://api.openweathermap.org/data/2.5/forecast?lat=55.7231&lon=84.8861&appid=84805020d86bc2c126e436cb87fe6c67&units=metric';

        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception('failed to get data from open weather');
        }

        return json_decode($response);
    }

    private function mapForecast(stdClass $forecastObject): ThreeHoursForecastDto
    {
        $forecastDto = new ThreeHoursForecastDto();

        $forecastDto->datetime_unix = $forecastObject->dt;
        $forecastDto->datetime_text = $forecastObject->dt_txt;
        $forecastDto->temperature = $forecastObject->main->temp;
        $forecastDto->temperature_feels_like = $forecastObject->main->feels_like;
        $forecastDto->humidity = $forecastObject->main->humidity;
        $forecastDto->pressure = $forecastObject->main->pressure;
        $forecastDto->wind_speed = $forecastObject->wind->speed;
        $forecastDto->created_at = now();

        return $forecastDto;
    }
}
