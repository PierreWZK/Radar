<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Chaque nuit, les mesures du jour partent dans la file (queue:work doit tourner).
Schedule::command('radar:mesurer')->dailyAt('03:00')->withoutOverlapping();
