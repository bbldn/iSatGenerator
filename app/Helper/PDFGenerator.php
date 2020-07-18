<?php

namespace App\Helper;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PDFGenerator implements GeneratorInterface
{
    /**
     * @param array $categories
     * @param int $customerGroupId
     * @throws \Mpdf\MpdfException
     */
    public function generateAndSave(array $categories, int $customerGroupId): void
    {
        $data = [
            'categories' => $categories,
            'customerGroupId' => $customerGroupId,
            'now' => date('Y-m-d H:i:s'),
        ];

        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view('html', $data)->toHtml());
        $mpdf->Output(storage_path("app/{$customerGroupId}.pdf"), Destination::FILE);
    }
}
