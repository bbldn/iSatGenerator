<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class HTMLGenerator implements GeneratorInterface
{
    /**
     * @param array $categories
     * @param int $customerGroupId
     */
    public function generateAndSave(array $categories, int $customerGroupId): void
    {
        $data = [
            'categories' => $categories,
            'customerGroupId' => $customerGroupId,
            'now' => date('Y-m-d H:i:s'),
        ];

        Storage::put("{$customerGroupId}.html", view('html', $data)->toHtml());
    }
}
