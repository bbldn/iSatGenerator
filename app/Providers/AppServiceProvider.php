<?php

namespace App\Providers;

use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->app->singleton(ProductService::class, function () {
            return new ProductService(env('SITE_URL', ''));
        });
    }
}
