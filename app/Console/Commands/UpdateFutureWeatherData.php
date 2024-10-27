<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateFutureWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-future-weather-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(private readonly OpenWeatherApiService $weatherService) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::alert("update future weather started");
        $this->weatherService->updateFutureWeatherData();
        Log::alert("update future weather ended");
    }
}
