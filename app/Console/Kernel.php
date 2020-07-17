<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var string[] $commands
     */
    protected $commands = [
        \Illuminate\Console\KeyGenerateCommand::class,
        \App\Console\Commands\GenerateJSONCommand::class,
        \App\Console\Commands\GenerateXLSXCommand::class,
        \App\Console\Commands\GenerateHTMLCommand::class,
        \App\Console\Commands\GeneratePDFCommand::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  Schedule $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule): void
    {
    }
}
