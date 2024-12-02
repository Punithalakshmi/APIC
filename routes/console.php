<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();


//Schedule::command('coohom:cron')->weekly()->mondays()->at('06:00');
//Schedule::command('coohom:cron')->weekly()->mondays()->at('06:00');
Schedule::command('app:test-cr-job')->everyMinute();
