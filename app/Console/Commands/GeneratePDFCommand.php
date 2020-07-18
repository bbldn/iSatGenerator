<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\PDFGenerator;
use App\Helper\Store;
use App\Services\ProductService;
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
     * @param ProductService $productService
     * @param PDFGenerator $generator
     * @param FileUploader $uploader
     * @throws MpdfException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(ProductService $productService, PDFGenerator $generator, FileUploader $uploader): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
            $uploader->send(storage_path("app/{$groupId}.pdf"), "/var/www/files/{$groupId}.pdf");
        }
    }
}
