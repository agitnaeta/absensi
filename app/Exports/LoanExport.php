<?php

namespace App\Exports;

use App\Models\Loan;
use App\Repositories\LoanRepository;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LoanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return LoanRepository::recap();
    }


    public function headings(): array
    {
       return [
           'UserId',
           'Karyawan',
           'Jumlah Kasbon',
           'Terbayar',
           'Sisa',
       ];
    }
}
