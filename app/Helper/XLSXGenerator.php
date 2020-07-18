<?php

namespace App\Helper;

use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XLSXGenerator implements GeneratorInterface
{
    /**
     * @param Worksheet $sheet
     * @param int $customerGroupId
     */
    protected function fillHead(Worksheet $sheet, int $customerGroupId): void
    {
        $sheet->setTitle('Цены ' . date('Y.m.d H.i.s'));
        $sheet->setCellValue('A1', 'Наименование');
        $sheet->setCellValue('B1', 'Ссылка');
        $sheet->setCellValue('C1', 'Розница');

        switch ($customerGroupId) {
            case 2:
                $sheet->setCellValue('D1', 'Дилер');
                break;
            case 3:
                $sheet->setCellValue('D1', 'ОПТ');
                break;
            case 4:
                $sheet->setCellValue('D1', 'Партнет');
                break;
        }

        $sheet->getColumnDimension('A')->setWidth(70);
        $sheet->getColumnDimension('B')->setWidth(70);
    }

    /**
     * @param Worksheet $sheet
     * @param array $category
     * @param int $customerGroupId
     * @param int $line
     * @throws PhpSpreadsheetException
     */
    protected function fillCategory(Worksheet $sheet, array $category, int $customerGroupId, int $line): void
    {
        if ($customerGroupId > 1) {
            $range = 'A' . $line . ':D' . ($line + 1);
            $sheet->mergeCells($range);
        } else {
            $range = 'A' . $line . ':C' . ($line + 1);
            $sheet->mergeCells($range);
        }

        $sheet->setCellValue("A{$line}", $category['name']);
        $sheet->getStyle("A{$line}")->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ]
        ]);
    }

    /**
     * @param Worksheet $sheet
     * @param array $product
     * @param int $customerGroupId
     * @param int $line
     */
    protected function fillProduct(Worksheet $sheet, array $product, int $customerGroupId, int $line): void
    {
        $sheet->setCellValue("A{$line}", $product['name']);
        $sheet->setCellValue("B{$line}", $product['url']);
        $sheet->setCellValue("C{$line}", $product['retail']);
        switch ($customerGroupId) {
            case 2:
                $sheet->setCellValue("D{$line}", $product['dealer']);
                break;
            case 3:
                $sheet->setCellValue("D{$line}", $product['wholesale']);
                break;
            case 4:
                $sheet->setCellValue("D{$line}", $product['partner']);
                break;
        }
    }

    /**
     * @param array $categories
     * @param int $customerGroupId
     * @return mixed|void
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     */
    public function generateAndSave(array $categories, int $customerGroupId): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->fillHead($sheet, $customerGroupId);

        $line = 2;
        foreach ($categories as $category) {
            $this->fillCategory($sheet, $category, $customerGroupId, $line);

            $line += 2;
            foreach ($category['products'] as $product) {
                $this->fillProduct($sheet, $product, $customerGroupId, $line);
                $line++;
            }
        }

        $writer = new Xlsx($spreadsheet);
        $writer->save(storage_path("app/{$customerGroupId}.xlsx"));
    }
}
