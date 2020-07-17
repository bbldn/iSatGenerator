<?php

namespace App\Helper;

use Dompdf\Dompdf;
use Illuminate\Support\Facades\Storage;

class PDFGenerator implements GeneratorInterface
{
    /** @var Dompdf $domPdf */
    protected $domPdf;

    /**
     * PDFGenerator constructor.
     * @param Dompdf $domPdf
     */
    public function __construct(Dompdf $domPdf)
    {
        $this->domPdf = $domPdf;
    }

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

        $this->domPdf->loadHtml(view('html', $data)->toHtml());
        $this->domPdf->setPaper('A4', 'landscape');
        $this->domPdf->render();
        $this->domPdf->output();

        Storage::put("{$customerGroupId}.pdf", $this->domPdf->output());
    }
}
