<?php

namespace App\Http\Controllers;

use App\Repository\WeatherRepository;
use App\Services\ForecastService;

class IndexController extends Controller
{
    public function __construct(private readonly ForecastService $forecastService) {}

    public function __invoke()
    {
        $fullForecast = $this->forecastService->getFullForecast();
        return response()->json($fullForecast);
    }
}
