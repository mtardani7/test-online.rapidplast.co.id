<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    ShouldAutoSize,
    WithEvents,
    WithHeadings,
    WithStyles,
    WithTitle
};
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;

class DetailSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithTitle
{
    protected Collection $users;
    protected ?array $rows = null;

    public function __construct($users)
    {
        $this->users = collect($users);
    }

    public function array(): array
    {
        if ($this->rows !== null) {
            return $this->rows;
        }

        $rows = [];

        foreach ($this->users as $user) {
            foreach ($user->attempts->sortByDesc('created_at') as $attempt) {
                $test = $attempt->tests;
                if (!$test) {
                    continue;
                }

                $rows[] = [
                    count($rows) + 1,
                    $user->name,
                    $user->email,
                    $test->name,
                    $test->type ?? '-',
                    $attempt->score,
                    $attempt->iq_score ?? '-',
                    $attempt->iq_category ?? '-',
                    $attempt->created_at?->format('Y-m-d H:i:s') ?? '-',
                ];
            }
        }

        return $this->rows = $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama Kandidat',
            'Email',
            'Nama Tes',
            'Tipe Tes',
            'Skor',
            'IQ',
            'Kategori IQ',
            'Waktu Pengerjaan',
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '9F1239']],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER],
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event): void {
                $sheet = $event->sheet->getDelegate();
                $sheet->freezePane('A2');
                $sheet->setAutoFilter('A1:' . $sheet->getHighestColumn() . $sheet->getHighestRow());
            },
        ];
    }

    public function title(): string
    {
        return 'Detail Attempt';
    }
}
