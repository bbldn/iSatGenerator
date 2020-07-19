<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
use App\Helper\FileUploader;
use App\Helper\PDFGenerator;
use App\Helper\Store;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use Mpdf\MpdfException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeneratePDFCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:pdf';

    /** @var string $description */
    protected $description = 'Generate PDF';

    /**
     * @param GeneratorService $generatorService
     * @param PDFGenerator $generator
     * @param FileUploader $uploader
     * @throws MpdfException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws FileUploaderException
     */
    public function handle(GeneratorService $generatorService, PDFGenerator $generator, FileUploader $uploader): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(storage_path("app/{$groupId}.pdf"), "/var/www/files/{$groupId}.pdf");
        }
    }
}
