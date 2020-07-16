<?php

namespace App\Listeners;

use App\Events\ExampleEvent;

class ExampleListener
{
    /**
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * @param  ExampleEvent $event
     * @return void
     */
    public function handle(ExampleEvent $event): void
    {
    }
}
