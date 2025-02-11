<?php

namespace App\Exports;

use App\Models\Student;
use Maatwebsite\Excel\Concerns\FromCollection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Illuminate\Support\Facades\Response;

class StudentsExport
{
    /**
     * @return \Illuminate\Support\Collection
     */
   
    public function export()
    {
        $students = Student::with('teacher')->select('student_name', 'class_teacher_xid', 'class', 'admission_date', 'yearly_fees')->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = [
            'A1' => 'Sr. No.', 
            'B1' => 'Student Name',
            'C1' => 'Class Teacher Name',
            'D1' => 'Class',
            'E1' => 'Admission Date',
            'F1' => 'Yearly Fees (₹)',
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }

        $headerStyle = [
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '87CEEB'], // Sky Blue Color
            ],
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'], // White text
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        $sheet->getStyle('A1:F1')->applyFromArray($headerStyle);

        $sheet->getRowDimension(1)->setRowHeight(25);

        $row = 2;
        $srNo = 1;
        foreach ($students as $student) {
            $sheet->setCellValue('A' . $row, $srNo); 
            $sheet->setCellValue('B' . $row, $student->student_name);
            $sheet->setCellValue('C' . $row, $student->teacher->teacher_name ?? 'N/A');
            $sheet->setCellValue('D' . $row, $student->class);
            $sheet->setCellValue('E' . $row, $student->admission_date);
            $sheet->setCellValue('F' . $row, '₹' . number_format($student->yearly_fees, 2)); // Add ₹ symbol and format

            $row++;
            $srNo++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'students.xlsx';

        $filePath = storage_path('app/' . $fileName);
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);

        return Response::download($filePath)->deleteFileAfterSend(true);
    }
}
