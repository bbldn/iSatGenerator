<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\JSONGenerator;
use App\Helper\Store;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateJSONCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:json';

    /** @var string $description */
    protected $description = 'Generate JSON';

    /**
     * @param ProductService $productService
     * @param JSONGenerator $generator
     * @param FileUploader $uploader
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(ProductService $productService, JSONGenerator $generator, FileUploader $uploader): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
            $uploader->send(storage_path("app/{$groupId}.json"), "/var/www/files/{$groupId}.json");
        }
    }
}
