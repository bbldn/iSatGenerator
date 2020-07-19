<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
use App\Helper\FileUploader;
use App\Helper\JSONGenerator;
use App\Helper\Store;
use App\Services\GeneratorService;
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
     * @param GeneratorService $generatorService
     * @param JSONGenerator $generator
     * @param FileUploader $uploader
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws FileUploaderException
     */
    public function handle(GeneratorService $generatorService, JSONGenerator $generator, FileUploader $uploader): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(storage_path("app/{$groupId}.json"), "/var/www/files/{$groupId}.json");
        }
    }
}
