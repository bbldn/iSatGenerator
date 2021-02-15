<?php

namespace App\Console;

use Laravel\Lumen\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * @var string[]
     * @psalm-var class-string[]
     */
    protected $commands = [
        \Illuminate\Console\KeyGenerateCommand::class,
        \App\Console\Commands\GeneratePDFCommand::class,
        \App\Console\Commands\GenerateAllCommand::class,
        \App\Console\Commands\GenerateJSONCommand::class,
        \App\Console\Commands\GenerateXLSXCommand::class,
        \App\Console\Commands\GenerateHTMLCommand::class,
    ];
}
