<?php

use Illuminate\Support\Facades\Schedule;

Schedule::command('etfs:run-ai-extraction')
    ->dailyAt('00:05')
    ->withoutOverlapping();

Schedule::command('etf:calculate-metrics')
    ->dailyAt('02:00')
    ->withoutOverlapping();
