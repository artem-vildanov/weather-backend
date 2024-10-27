<?php

namespace App\Http\Controllers;

use App\Repository\WeatherRepository;
use App\Services\ForecastService;
use App\Services\OpenWeatherApiService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private readonly ForecastService $forecastService, private OpenWeatherApiService $openWeatherApiService) {}

    public function __invoke(): JsonResponse
    {
        $fullForecast = $this->forecastService->getFullForecast();
        return response()->json($fullForecast);
    }

    public function test(): JsonResponse
    {
        $this->openWeatherApiService->updateCurrentWeatherData();
        return response()->json();
    }
}
