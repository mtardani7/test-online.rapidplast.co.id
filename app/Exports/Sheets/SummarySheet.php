<?php

namespace App\Exports\Sheets;

use App\Models\User;
use App\Models\Tests;
use App\Models\Attempt;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummarySheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    protected $users;
    protected $startDate;
    protected $endDate;
    protected $tests;

    public function __construct($users, $startDate, $endDate)
    {
        $this->users     = $users;
        $this->startDate = Carbon::parse($startDate);
        $this->endDate   = Carbon::parse($endDate);

        /**
         * 🔥 KOLOM TEST DIAMBIL DARI ATTEMPTS
         * test yang benar-benar dikerjakan user
         */
        $this->tests = Tests::whereIn(
            'id',
            Attempt::whereBetween('created_at', [$this->startDate, $this->endDate])
                ->pluck('test_id')
                ->unique()
        )
        ->orderBy('id')
        ->get();
    }

    public function array(): array
    {
        $rows = [];
        $no   = 1;

        foreach ($this->users as $user) {

            $attempts = $user->attempts
                ->whereBetween('created_at', [$this->startDate, $this->endDate]);

            if ($attempts->isEmpty()) continue;

            $row = [
                $no++,
                $user->name,
                $user->email,
                $attempts->count(),
            ];

            /**
             * 🔒 URUTAN KOLOM DIKUNCI OLEH $this->tests
             */
            foreach ($this->tests as $test) {
                $attempt = $attempts
                    ->where('test_id', $test->id)
                    ->sortByDesc('created_at')
                    ->first();

                $row[] = $attempt?->score ?? '-';
            }

            // Average score dari attempts (konsisten)
            $row[] = round($attempts->avg('score'), 2);

            $rows[] = $row;
        }

        return $rows;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Nama',
            'Email',
            'Jumlah Tes',
        ];

        foreach ($this->tests as $test) {
            $headings[] = $test->name;
        }

        $headings[] = 'Average Score';

        return $headings;
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
