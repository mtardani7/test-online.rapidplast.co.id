<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\DetailSheet;

class TotalTestExport implements WithMultipleSheets
{
    protected $users;

    public function __construct($users)
    {
        $this->users = $users;
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->users),
            new DetailSheet($this->users),
        ];
    }
}

