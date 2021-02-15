<?php

namespace App\Helper;

use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;

class PDFGenerator implements GeneratorInterface
{
    /**
     * @param array $data
     * @param int $customerGroupId
     * @throws MpdfException
     */
    public function generateAndSave(array $data, int $customerGroupId): void
    {
        $data['now'] = date('Y-m-d H:i:s');
        $data['customerGroupId'] = $customerGroupId;
        if ($customerGroupId > 1) {
            $data['currency'] = $data['currency']['USD'];
        } else {
            $data['currency'] = $data['currency']['UAH'];
        }

        $mpdf = new Mpdf();
        $mpdf->WriteHTML(view('html', $data)->toHtml());
        $mpdf->Output(storage_path("app/{$customerGroupId}.pdf"), Destination::FILE);
    }
}
