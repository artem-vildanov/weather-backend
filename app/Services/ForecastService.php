<?php

namespace App\Services;

use App\Dto\FullForecastDto;
use App\Repository\WeatherRepository;
use Carbon\Carbon;

class ForecastService
{
    public const FORECAST_DAYS = 5;

    public function __construct(private readonly WeatherRepository $weatherRepository) {}

    public function getFullForecast(): array
    {
        $forecasts = $this->weatherRepository->getWeatherData();

        $this->convertArrayData($forecasts);

        return $this->divideByDays($forecasts);
    }

    private function divideByDays(array $forecasts): array
    {
        $fullForecast = [];
        $filterDate = Carbon::today();

        for ($dayNumber = 0; $dayNumber < ForecastService::FORECAST_DAYS; $dayNumber++) {
            $filterByDayCallable = function($forecast) use ($filterDate) {
                $forecast = (array)$forecast;
                return substr($forecast['datetime_text'], 0, 10) === $filterDate->format('Y-m-d');
            };

            $dayForecast = (array)array_filter($forecasts, $filterByDayCallable);
            $fullForecast[] = array_values($dayForecast);
            $filterDate->addDay();
        }

        return $fullForecast;
    }

    private function convertArrayData(array $forecasts): void
    {
        foreach ($forecasts as $period) {
            // Добавляем 7 часов к UTC времени в периоде
            $datetime = Carbon::parse($period->datetime_text);
            $datetime->addHours(7);
            $period->datetime_text = $datetime->format('Y-m-d H:i:s');

            // Переводим давление из гПа в мм. рт. ст.
            $period->pressure *= 0.75;
        }
    }
}
