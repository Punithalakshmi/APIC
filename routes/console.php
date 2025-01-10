<?php

use Illuminate\Foundation\Inspiring;
use App\Services\ApiMonitoringService;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->at('10:04');


Schedule::command('coohom:cron')->mondays()->at('00:30');

//Schedule::command('app:test-job')->everyFiveMinutes();
Schedule::command('jobs:fetch-update')->at('10:04');


