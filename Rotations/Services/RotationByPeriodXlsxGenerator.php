<?php


namespace App\Modules\Rotations\Services;


use App\Modules\Rotations\Models\Rotation;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RotationByPeriodXlsxGenerator
{
    const STATUSES = [
        'new'        => 'Новый',
        'passed'     => 'Прошел',
        'not-passed' => 'Не прошел',
    ];

    public function __construct(private Request $request){}


    public function generate()
    {
        $rotation = Rotation::with([
            'city',
            'periods'      => function ($query) {
                return $query->where('id', $this->request->period_id);
            },
            'userRotation' => function ($query) {
                $query->where('rotation_period_id', $this->request->period_id);
            },
            'userRotation.answers',
            'userRotation.user',
        ])->find($this->request->rotation_id);


        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Ротация по периоду');

        $this->setHeader($spreadsheet, $sheet);
        $row = 2;

        $rotationPeriod = $rotation->periods->first();
        $startDate = $rotationPeriod->start_date;
        $endDate = $rotationPeriod->end_date;

        foreach ($rotation->userRotation as $userRotation) {
            $sheet->setCellValue('A' . $row, $userRotation->user->fullname);
            $sheet->setCellValue('B' . $row, $userRotation->answers->first()->created_at);
            $sheet->setCellValue('C' . $row, $rotation->city->name);
            $sheet->setCellValue('D' . $row, $startDate);
            $sheet->setCellValue('E' . $row, $endDate);
            $sheet->setCellValue('F' . $row, self::STATUSES[$userRotation->status]);
            $sheet->setCellValue('G' . $row, $rotation->name);
            $row++;
        }

        $sheet->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = new Xlsx($spreadsheet);
        $filename = "Ротация по периоду.xlsx";
        $path = public_path("documents/$filename");
        $writer->save($path);

        return $path;
    }

    private function setHeader($spreadsheet, $sheet): int
    {
        $headers = [
            'ФИО',
            'Дата подачи',
            'Город',
            'Дата открытия',
            'Дата закрытия',
            'Статус',
            'Место подачи'
        ];

        $spreadsheet->getActiveSheet()->fromArray($headers, NULL, 'A1');
        $spreadsheet->getActiveSheet()->getStyle('A1:G1')->getFont()->setBold(true);

        foreach (range('A', 'G') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return count($headers);
    }
}
