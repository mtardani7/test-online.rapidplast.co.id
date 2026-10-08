<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use App\Exports\Sheets\SummarySheet;
use App\Exports\Sheets\DetailSheet;
use Illuminate\Support\Carbon;

class TotalTestExport implements WithMultipleSheets
{
    protected $users;
    protected $startDate;
    protected $endDate;

    public function __construct($users, $startDate, $endDate)
    {
        $this->users = $users;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    public function sheets(): array
    {
        return [
            new SummarySheet($this->users, $this->startDate, $this->endDate),
            new DetailSheet($this->users, $this->startDate, $this->endDate),
        ];
    }
}

