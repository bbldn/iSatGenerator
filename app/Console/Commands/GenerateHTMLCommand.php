<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\HTMLGenerator;
use App\Helper\Store;
use App\Services\ProductService;
use Illuminate\Console\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateHTMLCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:html';

    /** @var string $description */
    protected $description = 'Generate HTML';

    /**
     * @param ProductService $productService
     * @param HTMLGenerator $generator
     * @param FileUploader $uploader
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(ProductService $productService, HTMLGenerator $generator, FileUploader $uploader): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
            $uploader->send(storage_path("app/{$groupId}.html"), "/var/www/files/{$groupId}.html");
        }
    }
}
