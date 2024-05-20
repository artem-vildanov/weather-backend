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

    public function updateWeatherData(): void
    {
        $forecast = $this->getForecast();
        // Log::error($forecast);
        $this->weatherRepository->deleteWeatherData();
        $this->weatherRepository->insertWeatherData($forecast);
    }
    /** @return ForecastDto[] */
    private function getForecast(): array
    {
        /** @var ForecastDto[] */
        $forecastData = [];
        $forecastData[] = $this->getCurrentWeatherData();

        $forecastData = array_merge($forecastData, $this->getFutureWeatherData());

        return $forecastData;
    }

    private function getCurrentWeatherData(): ForecastDto
    {
        $currentWeatherData = $this->fetchWeatherData($this->CURRENT_WEATHER);
        return $this->mapForecast($currentWeatherData);
    }

    /**
     * @return ForecastDto[]
     */
    private function getFutureWeatherData(): array
    {
        $futureWeatherData = $this->fetchWeatherData($this->FUTURE_FORECAST);

        /** @var ForecastDto[] */
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

        $forecastDto->datetime_text = $this->formatDate($forecastObject->dt);
        $forecastDto->temperature = $forecastObject->main->temp;
        $forecastDto->temperature_feels_like = $forecastObject->main->feels_like;
        $forecastDto->humidity = $forecastObject->main->humidity;
        $forecastDto->pressure = $forecastObject->main->pressure;
        $forecastDto->wind_speed = $forecastObject->wind->speed;
        $forecastDto->created_at = (string)now();

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
