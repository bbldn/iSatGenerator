<?php

namespace App\Console\Commands;

use App\Helper\JSONGenerator;
use App\Helper\Store;
use App\Services\ProductService;
use Illuminate\Console\Command;

class GenerateJSONCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:json';

    /** @var string $description */
    protected $description = 'Generate JSON';

    /**
     * @param ProductService $productService
     * @param JSONGenerator $generator
     */
    public function handle(ProductService $productService, JSONGenerator $generator): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
        }
    }
}
