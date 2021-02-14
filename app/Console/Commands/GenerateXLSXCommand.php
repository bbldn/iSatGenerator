<?php

namespace App\Console\Commands;

use App\Exceptions\FileUploaderException;
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
    /** @var string */
    protected $signature = 'generate:xlsx';

    /** @var string */
    protected $description = 'Generate XLSX';

    /**
     * @param FileUploader $uploader
     * @param XLSXGenerator $generator
     * @param GeneratorService $generatorService
     * @throws ClientExceptionInterface
     * @throws FileUploaderException
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function handle(
        FileUploader $uploader,
        XLSXGenerator $generator,
        GeneratorService $generatorService
    ): void
    {
        $generatorService->init();
        $data = $generatorService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($data, $groupId);
            $uploader->send(
                storage_path("app/{$groupId}.xlsx"),
                "/var/www/files/{$groupId}.xlsx"
            );
        }
    }
}
