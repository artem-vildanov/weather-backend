<?php

namespace App\Services;

use App\Dto\ForecastDto;
use App\Repository\WeatherRepository;
use DateTime;
use Illuminate\Support\Facades\Log;
use stdClass;
use Exception;

class OpenWeatherApiService
{
    private $BASE_URL = "https://api.openweathermap.org/data/2.5/";
    private $REQUEST_PARAMS = "?lat=55.7231&lon=84.8861&appid=84805020d86bc2c126e436cb87fe6c67&units=metric";
    private $FUTURE_FORECAST = 'forecast';
    private $CURRENT_WEATHER = 'weather';

    public function __construct(private readonly WeatherRepository $weatherRepository) {}

    public function updateCurrentWeatherData(): void
    {
        $this->weatherRepository->updateCurrentWeatherData($this->getCurrentWeatherData());
    }

    public function updateFutureWeatherData(): void
    {
        $this->weatherRepository->updateFutureWeather($this->getFutureWeatherData());
    }

    private function getCurrentWeatherData(): ForecastDto
    {
        $currentWeatherData = $this->fetchWeatherData($this->CURRENT_WEATHER);
        return $this->mapForecast($currentWeatherData);
    }

    /**
     * @return ForecastDto[]
     * @throws Exception
     */
    private function getFutureWeatherData(): array
    {
        $futureWeatherData = $this->fetchWeatherData($this->FUTURE_FORECAST);

        /** @var ForecastDto[] $forecastsDtoGroup */
        $forecastsDtoGroup = [];

        foreach($futureWeatherData->list as $forecast)
        {
            $forecastsDtoGroup[] = $this->mapForecast($forecast);
        }

        return $forecastsDtoGroup;
    }

    private function fetchWeatherData(string $requestType): stdClass
    {
        $url = $this->BASE_URL . $requestType . $this->REQUEST_PARAMS;

        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception('failed to get data from open weather');
        }

        return json_decode($response);
    }

    private function mapForecast(stdClass $forecastObject): ForecastDto
    {
        $forecastDto = new ForecastDto();

        $forecastDto->datetimeText = $this->formatDate($forecastObject->dt);
        $forecastDto->temperature = $forecastObject->main->temp;
        $forecastDto->temperatureFeelsLike = $forecastObject->main->feels_like;
        $forecastDto->humidity = $forecastObject->main->humidity;
        $forecastDto->pressure = $forecastObject->main->pressure * 0.75;
        $forecastDto->windSpeed = $forecastObject->wind->speed;
        $forecastDto->createdAt = (string)now();

        return $forecastDto;
    }

    /** @return string date in d-m-Y H:i format */
    private function formatDate(int $datetimeUnix): string
    {
        $hoursToAdd = 7;

        $date = new DateTime();
        $date->setTimestamp($datetimeUnix);
        $date->modify("+{$hoursToAdd} hours");
        $formattedDate = $date->format('d-m-Y H:i');

        return $formattedDate;
    }
}
