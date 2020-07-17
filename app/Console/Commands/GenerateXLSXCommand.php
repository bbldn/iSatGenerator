<?php

namespace App\Console\Commands;

use App\Helper\Store;
use App\Helper\XLSXGenerator;
use App\Services\ProductService;
use Illuminate\Console\Command;

class GenerateXLSXCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:xlsx';

    /** @var string $description */
    protected $description = 'Generate XLSX';

    /**
     * @param ProductService $productService
     * @param XLSXGenerator $generator
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function handle(ProductService $productService, XLSXGenerator $generator): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
        }
    }
}
