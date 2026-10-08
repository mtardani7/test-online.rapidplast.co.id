<?php

namespace App\Exports\Sheets;

use App\Models\User;
use Maatwebsite\Excel\Concerns\{
    FromArray,
    WithHeadings,
    WithStyles,
    ShouldAutoSize
};
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailSheet implements FromArray, WithHeadings, WithStyles, ShouldAutoSize
{
    public function array(): array
    {
        $users = User::whereDoesntHave('roles', function ($q) {
            $q->where('name', 'admin');
        })
        ->with('attempts.tests')
        ->get();

        $rows = [];
        $no   = 1;

        foreach ($users as $user) {
            foreach ($user->attempts->sortBy('test_id') as $attempt) {

                if (!$attempt->test) continue;

                $rows[] = [
                    $no++,
                    $user->name,
                    $attempt->test->name,
                    $attempt->test->type ?? '-',
                    $attempt->score,
                    $attempt->created_at->format('Y-m-d'),
                ];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'Nama Test',
            'Tipe Test',
            'Score',
            'Tanggal Test',
        ];
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
