<?php

namespace App\Console\Commands;

use App\Helper\PDFGenerator;
use App\Helper\Store;
use App\Services\ProductService;
use Illuminate\Console\Command;

class GeneratePDFCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:pdf';

    /** @var string $description */
    protected $description = 'Generate PDF';

    /**
     * @param ProductService $productService
     * @param PDFGenerator $generator
     */
    public function handle(ProductService $productService, PDFGenerator $generator): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
        }
    }
}
