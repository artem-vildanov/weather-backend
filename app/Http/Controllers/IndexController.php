<?php

namespace App\Http\Controllers;

use App\Repository\WeatherRepository;
use App\Services\ForecastService;
use Illuminate\Http\JsonResponse;

class IndexController extends Controller
{
    public function __construct(private readonly ForecastService $forecastService) {}

    public function __invoke(): JsonResponse
    {
        $fullForecast = $this->forecastService->getFullForecast();
        return response()->json($fullForecast);
    }
}
