<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\HTMLGenerator;
use App\Helper\Store;
use App\Services\GeneratorService;
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
     * @param GeneratorService $generatorService
     * @param HTMLGenerator $generator
     * @param FileUploader $uploader
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(GeneratorService $generatorService, HTMLGenerator $generator, FileUploader $uploader): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(storage_path("app/{$groupId}.html"), "/var/www/files/{$groupId}.html");
        }
    }
}
