<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
use App\Helper\FileUploader;
use App\Helper\JSONGenerator;
use App\Helper\StoreContext;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateJSONCommand extends Command
{
    /** @var string */
    protected $signature = 'generate:json';

    /** @var string */
    protected $description = 'Generate JSON';

    /**
     * @param FileUploader $uploader
     * @param JSONGenerator $generator
     * @param GeneratorService $generatorService
     * @throws ClientExceptionInterface
     * @throws FileUploaderException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function handle(
        FileUploader $uploader,
        JSONGenerator $generator,
        GeneratorService $generatorService
    ): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (StoreContext::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(
                storage_path("app/{$groupId}.json"),
                "/var/www/files/{$groupId}.json"
            );
        }
    }
}
