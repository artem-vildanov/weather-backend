<?php

namespace App\Services;

use App\Dto\ForecastDto;
use App\Repository\WeatherRepository;
use DateTime;

class ForecastService
{
    public const FORECAST_DAYS = 5;

    public function __construct(private readonly WeatherRepository $weatherRepository) {}

    /** @return ForecastDto[] */
    public function getFullForecast(): array
    {
        $forecasts = $this->weatherRepository->getWeatherData();
        return $this->divideByDays($forecasts);
    }

    /**
    * @param $forecasts ForecastDto[]
    * @return ForecastDto[]
    */
    private function divideByDays(array $forecasts): array
    {
        $fullForecast = [];
        $filterDate = $this->getCurrentDate();

        for ($dayNumber = 0; $dayNumber < ForecastService::FORECAST_DAYS; $dayNumber++) {
            $filterByDayCallable = function($forecast) use ($filterDate) {
                $forecastDate = substr($forecast->datetime_text, 0, 10);
                return $forecastDate === $filterDate;
            };

            $dayForecast = array_filter($forecasts, $filterByDayCallable);
            $fullForecast[] = array_values($dayForecast);
            $filterDate = $this->incrementDate($filterDate);
        }

        return $fullForecast;
    }

    private function getCurrentDate(): string
    {
        $hoursToAdd = 7;
        $timestamp = time();
        $currentDate = new DateTime();
        $currentDate->setTimestamp($timestamp);
        $currentDate->modify("+{$hoursToAdd} hours");
        return $currentDate->format('d-m-Y');
    }

    private function incrementDate(string $dateString): string
    {
        $dateObject = DateTime::createFromFormat('d-m-Y', $dateString);
        $dateObject->modify('+1 days');
        return $dateObject->format('d-m-Y');
    }
}
