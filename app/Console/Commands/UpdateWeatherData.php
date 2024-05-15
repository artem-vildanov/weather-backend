<?php

namespace App\Console\Commands;

use App\Services\OpenWeatherApiService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class UpdateWeatherData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-weather-data';

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
        $this->weatherService->updateWeatherData();
    }
}
