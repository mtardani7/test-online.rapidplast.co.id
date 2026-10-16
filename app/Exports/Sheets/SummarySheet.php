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

class SummarySheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize, WithEvents, WithTitle
{
    protected Collection $users;
    protected Collection $tests;
    protected ?array $rows = null;

    public function __construct($users)
    {
        $this->users = collect($users);
        $this->tests = $this->users
            ->flatMap(fn ($user) => $user->attempts->pluck('tests'))
            ->filter()
            ->unique('id')
            ->sortBy('id')
            ->values();
    }

    public function array(): array
    {
        if ($this->rows !== null) {
            return $this->rows;
        }

        $rows = [];

        foreach ($this->users as $user) {
            $attempts = $user->attempts;
            if ($attempts->isEmpty()) {
                continue;
            }

            $row = [
                count($rows) + 1,
                $user->name,
                $user->email,
                $attempts->count(),
            ];

            foreach ($this->tests as $test) {
                $attempt = $attempts
                    ->where('test_id', $test->id)
                    ->sortByDesc('created_at')
                    ->first();
                $row[] = $attempt?->score ?? '-';
            }

            $row[] = round($attempts->avg('score'), 2);
            $rows[] = $row;
        }

        return $this->rows = $rows;
    }

    public function headings(): array
    {
        return array_merge(
            ['No', 'Nama Kandidat', 'Email', 'Jumlah Attempt'],
            $this->tests->pluck('name')->all(),
            ['Rata-rata Skor']
        );
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
        return 'Ringkasan';
    }
}
