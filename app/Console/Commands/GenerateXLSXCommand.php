<?php

namespace App\Console\Commands;

use App\Helper\FileUploader;
use App\Helper\Store;
use App\Helper\XLSXGenerator;
use App\Services\GeneratorService;
use Illuminate\Console\Command;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class GenerateXLSXCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:xlsx';

    /** @var string $description */
    protected $description = 'Generate XLSX';

    /**
     * @param GeneratorService $generatorService
     * @param XLSXGenerator $generator
     * @param FileUploader $uploader
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     * @throws TransportExceptionInterface
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function handle(GeneratorService $generatorService, XLSXGenerator $generator, FileUploader $uploader): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(storage_path("app/{$groupId}.xlsx"), "/var/www/files/{$groupId}.xlsx");
        }
    }
}
