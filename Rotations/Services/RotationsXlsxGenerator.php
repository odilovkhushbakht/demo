<?php

namespace App\Modules\Rotations\Services;

use App\Modules\Rotations\Models\RotationPeriod;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RotationsXlsxGenerator
{
    public function generate(): string
    {
        $rotationPeriods = RotationPeriod::query()
            ->with([
                'rotation:id,name,city_id',
                'rotation.city:id,name_ru'
            ])->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Ротации');

        $this->setHeader($spreadsheet, $sheet);
        $row = 2;

        foreach ($rotationPeriods as $rotationPeriod) {
            $sheet->setCellValue('A' . $row, $rotationPeriod->rotation->name);
            $sheet->setCellValue('B' . $row, $rotationPeriod->start_date);
            $sheet->setCellValue('C' . $row, $rotationPeriod->end_date);
            $sheet->setCellValue('D' . $row, $rotationPeriod->rotation->city->name_ru);
            $row++;
        }

        $sheet->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = new Xlsx($spreadsheet);
        $filename = "Ротации.xlsx";
        $path = public_path("documents/$filename");
        $writer->save($path);

        return $path;
    }

    private function setHeader($spreadsheet, $sheet): int
    {
        $headers = [
            'Название',
            'Дата открытия',
            'Дата закрытия',
            'Город',
        ];

        $spreadsheet->getActiveSheet()->fromArray($headers, NULL, 'A1');
        $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return count($headers);
    }
}
