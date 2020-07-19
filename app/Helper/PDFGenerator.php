<?php

namespace App\Helper;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;

class PDFGenerator implements GeneratorInterface
{
    /**
     * @param array $data
     * @param int $customerGroupId
     * @throws \Mpdf\MpdfException
     */
    public function generateAndSave(array $data, int $customerGroupId): void
    {
        $data['customerGroupId'] = $customerGroupId;
        $data['now'] = date('Y-m-d H:i:s');
        if ($customerGroupId > 1) {
            $data['currency'] = $data['currency']['UAH'];
        } else {
            $data['currency'] = $data['currency']['USD'];
        }

        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view('html', $data)->toHtml());
        $mpdf->Output(storage_path("app/{$customerGroupId}.pdf"), Destination::FILE);
    }
}
