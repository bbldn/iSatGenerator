<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
use App\Helper\FileUploader;
use App\Helper\GeneratorInterface;
use App\Helper\HTMLGenerator;
use App\Helper\JSONGenerator;
use App\Helper\PDFGenerator;
use App\Helper\StoreContext;
use App\Helper\XLSXGenerator;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateAllCommand extends Command
{
    /** @var string */
    protected $signature = 'generate:all';

    /** @var string */
    protected $description = 'Generate All(JSON, HTML, XLSX, PDF)';

    /**
     * @param FileUploader $uploader
     * @param PDFGenerator $PDFGenerator
     * @param JSONGenerator $JSONGenerator
     * @param XLSXGenerator $XLSXGenerator
     * @param HTMLGenerator $HTMLGenerator
     * @param GeneratorService $generatorService
     * @throws ClientExceptionInterface
     * @throws FileUploaderException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function handle(
        FileUploader $uploader,
        PDFGenerator $PDFGenerator,
        JSONGenerator $JSONGenerator,
        XLSXGenerator $XLSXGenerator,
        HTMLGenerator $HTMLGenerator,
        GeneratorService $generatorService
    ): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        $array = [
            'json' => $JSONGenerator,
            'xlsx' => $XLSXGenerator,
            'html' => $HTMLGenerator,
            'pdf' => $PDFGenerator,
        ];

        foreach (StoreContext::groupsIds() as $groupId => $_) {
            foreach ($array as $extension => $generator) {
                /** @var GeneratorInterface $generator */
                $generator->generateAndSave($data, $groupId);
                $uploader->send(
                    storage_path("app/{$groupId}.{$extension}"),
                    "/var/www/files/{$groupId}.{$extension}"
                );
            }
        }
    }
}
