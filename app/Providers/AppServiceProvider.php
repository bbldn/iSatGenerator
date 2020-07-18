<?php

namespace App\Providers;

use App\Helper\FileUploader;
use App\Services\ProductService;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

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

        $this->app->singleton(HttpClientInterface::class, function () {
            return HttpClient::create();
        });

        $this->app->singleton(FileUploader::class, function ($app) {
            return new FileUploader(
                $app[HttpClientInterface::class],
                env('HANDLER_URL', ''),
                env('HANDLER_TOKEN', '')
            );
        });
    }
}
