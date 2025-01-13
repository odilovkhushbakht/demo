<?php


namespace App\Modules\Rotations\Services;

use App\Modules\Rotations\Models\UserRotation;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RotationPollAnswersXlsxGenerator
{
    public function generate(int $rotationId): string
    {
        $userRotations = UserRotation::query()
            ->with([
                'user:id,name,surname,patronymic',
                'answers',
                'answers.poll',
                'period'
                ])->whereHas('period', function ($query) use ($rotationId) {
                $query->where('rotation_id', $rotationId);
            })->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet()->setTitle('Ротация');

        $this->setHeader($spreadsheet, $sheet);
        $row = 2;
        $odd = true;
        foreach ($userRotations as $userRotation) {
            $sheet->setCellValue('A' . $row, $userRotation->user->fullname);
            $userAnswers = $userRotation->answers;
            $sheet->setCellValue('B' . $row, $userAnswers->first()?->created_at);

            if ($userAnswers->count() > 0) {
                $mergeToCell = $row + $userAnswers->count() - 1;
                $sheet->mergeCells("A$row:A$mergeToCell");
                $sheet->mergeCells("B$row:B$mergeToCell");
            }

            if ($odd) {
                $mergeToCell = $row + $userAnswers->count();
                $mergeToCell = $userAnswers->count() > 0 ? $mergeToCell - 1 : $mergeToCell;
                $spreadsheet->getActiveSheet()
                    ->getStyle("A$row:D$mergeToCell")
                    ->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->getStartColor()
                    ->setARGB('DBE2E9');
                $odd = false;
            } else {
                $odd = true;
            }

            foreach ($userAnswers as $answer) {
                $sheet->setCellValue('C' . $row, $answer->poll->label);
                $sheet->setCellValue('D' . $row, implode($answer->answer));
                $sheet->setCellValue('E' . $row, $userRotation->period->start_date);
                $row++;
            }
        }

        $sheet->setAutoFilter($spreadsheet->getActiveSheet()->calculateWorksheetDimension());

        $writer = new Xlsx($spreadsheet);
        $time = now()->timestamp;
        $filename = "Ротация{$time}.xlsx";
        $path = public_path("documents/$filename");
        $writer->save($path);

        return $path;
    }

    private function setHeader($spreadsheet, $sheet): int
    {
        $headers = [
            'ФИО',
            'Дата подачи',
            'Вопрос',
            'Ответ',
            'Период'
        ];

        $spreadsheet->getActiveSheet()->fromArray($headers, NULL, 'A1');
        $spreadsheet->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

        foreach (range('A', 'D') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        return count($headers);
    }
}
