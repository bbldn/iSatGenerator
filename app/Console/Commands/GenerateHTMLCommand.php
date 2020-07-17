<?php

namespace App\Console\Commands;

use App\Helper\HTMLGenerator;
use App\Helper\Store;
use App\Services\ProductService;
use Illuminate\Console\Command;

class GenerateHTMLCommand extends Command
{
    /** @var string $signature */
    protected $signature = 'generate:html';

    /** @var string $description */
    protected $description = 'Generate HTML';

    public function handle(ProductService $productService, HTMLGenerator $generator): void
    {
        $productService->init();
        $categories = $productService->getData();

        foreach (Store::groupsIds() as $groupId => $_) {
            $generator->generateAndSave($categories, $groupId);
        }
    }
}
