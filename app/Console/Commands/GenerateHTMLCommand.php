<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
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
    /** @var string */
    protected $signature = 'generate:html';

    /** @var string */
    protected $description = 'Generate HTML';

    /**
     * @param FileUploader $uploader
     * @param HTMLGenerator $generator
     * @param GeneratorService $generatorService
     * @throws ClientExceptionInterface
     * @throws FileUploaderException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function handle(
        FileUploader $uploader,
        HTMLGenerator $generator,
        GeneratorService $generatorService
    ): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(
                storage_path("app/{$groupId}.html"),
                "/var/www/files/{$groupId}.html"
            );
        }
    }
}
