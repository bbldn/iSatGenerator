<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class HTMLGenerator implements GeneratorInterface
{
    /**
     * @param array $data
     * @param int $customerGroupId
     */
    public function generateAndSave(array $data, int $customerGroupId): void
    {
        $data['customerGroupId'] = $customerGroupId;
        $data['now'] = date('Y-m-d H:i:s');
        if ($customerGroupId > 1) {
            $data['currency'] = $data['currency']['USD'];
        } else {
            $data['currency'] = $data['currency']['UAH'];
        }

        Storage::put("{$customerGroupId}.html", view('html', $data)->toHtml());
    }
}
