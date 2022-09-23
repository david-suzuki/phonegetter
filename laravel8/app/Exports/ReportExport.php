<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;

class ReportExport implements FromCollection
{
    protected $histories;

    public function __construct(array $histories)
    {
        $this->histories = $histories;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return new Collection($this->histories);
    }
}
