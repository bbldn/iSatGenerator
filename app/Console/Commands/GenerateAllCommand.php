<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\GeneratorInterface;
use App\Helper\HTMLGenerator;
use App\Helper\JSONGenerator;
use App\Helper\PDFGenerator;
use App\Helper\Store;
use App\Helper\XLSXGenerator;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateAllCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:all';

    /** @var string $description */
    protected $description = 'Generate All(JSON, HTML, XLSX, PDF)';

    /**
     * @param GeneratorService $generatorService
     * @param JSONGenerator $JSONGenerator
     * @param XLSXGenerator $XLSXGenerator
     * @param HTMLGenerator $HTMLGenerator
     * @param PDFGenerator $PDFGenerator
     * @param FileUploader $uploader
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(
        GeneratorService $generatorService,
        JSONGenerator $JSONGenerator,
        XLSXGenerator $XLSXGenerator,
        HTMLGenerator $HTMLGenerator,
        PDFGenerator $PDFGenerator,
        FileUploader $uploader): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        $array = [
            'json' => $JSONGenerator,
            'xlsx' => $XLSXGenerator,
            'html' => $HTMLGenerator,
            'pdf' => $PDFGenerator,
        ];

        foreach (Store::groupsIds() as $groupId => $_) {
            foreach ($array as $extension => $generator) {
                /** @var GeneratorInterface $generator */
                $generator->generateAndSave($data, $groupId);
                $uploader->send(storage_path("app/{$groupId}.{$extension}"), "/var/www/files/{$groupId}.{$extension}");
            }
        }
    }
}
