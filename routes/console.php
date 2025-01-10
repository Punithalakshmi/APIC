<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->everyMinute();


//Schedule::command('coohom:cron')->everyMinute();

Schedule::command('app:test-job')->everyMinute();

