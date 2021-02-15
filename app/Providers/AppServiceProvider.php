<?php

namespace App\Providers;

use App\Helper\FileUploader;
use App\Repositories\CategoryRepository;
use App\Repositories\ProductCategoryRepository;
use App\Repositories\ProductDiscountRepository;
use App\Repositories\ProductRepository;
use App\Services\GeneratorService;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function register(): void
    {
        $this->app->singleton(HttpClientInterface::class, function () {
            return HttpClient::create();
        });

        $this->app->singleton(FileUploader::class, function (Application $app) {
            $fileUploader = new FileUploader($app[HttpClientInterface::class]);

            return $fileUploader->setUrl(env('HANDLER_URL'))->setToken(env('HANDLER_TOKEN'));
        });

        $this->app->singleton(GeneratorService::class, function (Application $app) {
            $generatorService = new GeneratorService(
                $app[ProductRepository::class],
                $app[CategoryRepository::class],
                $app[ProductCategoryRepository::class],
                $app[ProductDiscountRepository::class]
            );

            return $generatorService->setSiteUrl(env('SITE_URL'));
        });
    }
}
