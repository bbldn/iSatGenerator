<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
use App\Helper\FileUploader;
use App\Helper\PDFGenerator;
use App\Helper\StoreContext;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use Mpdf\MpdfException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GeneratePDFCommand extends Command
{
    /** @var string */
    protected $signature = 'generate:pdf';

    /** @var string */
    protected $description = 'Generate PDF';

    /**
     * @param FileUploader $uploader
     * @param PDFGenerator $generator
     * @param GeneratorService $generatorService
     * @throws ClientExceptionInterface
     * @throws FileUploaderException
     * @throws MpdfException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function handle(
        FileUploader $uploader,
        PDFGenerator $generator,
        GeneratorService $generatorService
    ): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (StoreContext::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(
                storage_path("app/{$groupId}.pdf"),
                "/var/www/files/{$groupId}.pdf"
            );
        }
    }
}
