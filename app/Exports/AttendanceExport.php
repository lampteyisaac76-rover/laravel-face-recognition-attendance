<?php

namespace App\Exports;

use App\Models\AttendanceSession;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Color;

class AttendanceExport implements
    FromCollection,
    WithHeadings,
    WithTitle,
    WithStyles,
    ShouldAutoSize
{
    protected AttendanceSession $session;

    public function __construct(AttendanceSession $session)
    {
        $this->session = $session;
    }

    public function collection()
    {
        $attendances = $this->session->attendances()
            ->with('student')
            ->orderBy('status', 'asc') // present first
            ->get();

        return $attendances->map(function ($record, $index) {
            return [
                'No.'          => $index + 1,
                'Index Number' => $record->student->index_number ?? '—',
                'Student Name' => $record->student->name ?? '—',
                'Email'        => $record->student->email ?? '—',
                'Status'       => strtoupper($record->status),
                'Method'       => $record->method ? ucfirst($record->method) : '—',
                'Marked At'    => $record->marked_at
                                    ? $record->marked_at->format('H:i:s')
                                    : '—',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'No.',
            'Index Number',
            'Student Name',
            'Email',
            'Status',
            'Method',
            'Time Marked',
        ];
    }

    public function title(): string
    {
        return $this->session->course->code
             . ' - '
             . $this->session->session_date->format('d M Y')
             . ' ('
             . ucfirst($this->session->period)
             . ')';
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            // Header row styling
            1 => [
                'font' => [
                    'bold'  => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size'  => 11,
                ],
                'fill' => [
                    'fillType'   => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF003366'],
                ],
            ],
        ];
    }
}