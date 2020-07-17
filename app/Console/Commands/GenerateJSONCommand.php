<?php

namespace App\Console\Commands;

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
     */
    public function handle(ProductService $productService): void
    {
        $productService->init();
        var_dump($productService->getData());
    }
}
