<?php

namespace App\Helper;

use App\Currency;
use PhpOffice\PhpSpreadsheet\Exception as PhpSpreadsheetException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception as PhpSpreadsheetWriterException;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class XLSXGenerator implements GeneratorInterface
{
    /** @var array{
     *      status?: int,
     *      code?: string,
     *      title?: string,
     *      value?: string,
     *      symbol_left?: string,
     *      currency_id?: integer,
     *      symbol_right?: string,
     *      decimal_place?: string,
     *      date_modified?: \DateTime,
     * }
     */
    private array $currency;

    /**
     * @param Worksheet $sheet
     * @param int $customerGroupId
     */
    private function fillHead(Worksheet $sheet, int $customerGroupId): void
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
    private function fillCategory(Worksheet $sheet, array $category, int $customerGroupId, int $line): void
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
                'wrapText' => true,
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);
    }

    /**
     * @param Worksheet $sheet
     * @param array $product
     * @param int $customerGroupId
     * @param int $line
     */
    private function fillProduct(Worksheet $sheet, array $product, int $customerGroupId, int $line): void
    {
        $sheet->setCellValue("A{$line}", $product['name']);
        $sheet->setCellValue("B{$line}", $product['url']);
        $sheet->setCellValue(
            "C{$line}",
            sprintf(
                '%s%s%s',
                $this->currency[Currency::symbolLeft],
                round($product['retail'] * $this->currency[Currency::value], $this->currency[Currency::decimalPlace]),
                $this->currency[Currency::symbolRight]
            )
        );

        switch ($customerGroupId) {
            case 2:
                $sheet->setCellValue(
                    "D{$line}",
                    sprintf(
                        '%s%s%s',
                        $this->currency[Currency::symbolLeft],
                        round($product['dealer'] * $this->currency[Currency::value], $this->currency[Currency::decimalPlace]),
                        $this->currency[Currency::symbolRight]
                    )
                );
                break;
            case 3:
                $sheet->setCellValue(
                    "D{$line}",
                    sprintf(
                        '%s%s%s',
                        $this->currency[Currency::symbolLeft],
                        round($product['wholesale'] * $this->currency[Currency::value], $this->currency[Currency::decimalPlace]),
                        $this->currency[Currency::symbolRight]
                    )
                );
                break;
            case 4:
                $sheet->setCellValue(
                    "D{$line}",
                    sprintf(
                        '%s%s%s',
                        $this->currency[Currency::symbolLeft],
                        round($product['partner'] * $this->currency[Currency::value], $this->currency[Currency::decimalPlace]),
                        $this->currency[Currency::symbolRight]
                    )
                );
                break;
        }
    }

    /**
     * @param array $data
     * @param int $customerGroupId
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     */
    private function createXLSX(array $data, int $customerGroupId): void
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $this->fillHead($sheet, $customerGroupId);

        $line = 2;
        foreach ($data['categories'] as $category) {
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

    /**
     * @param array $data
     * @param int $customerGroupId
     * @throws PhpSpreadsheetException
     * @throws PhpSpreadsheetWriterException
     */
    public function generateAndSave(array $data, int $customerGroupId): void
    {
        if ($customerGroupId > 1) {
            $this->currency = $data['currency']['USD'];
        } else {
            $this->currency = $data['currency']['UAH'];
        }

        $this->createXLSX($data, $customerGroupId);
    }
}
