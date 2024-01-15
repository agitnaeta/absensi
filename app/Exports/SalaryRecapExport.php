<?php

namespace App\Exports;

use App\Models\SalaryRecap;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalaryRecapExport implements FromCollection, WithHeadings, WithMapping
{

    protected $recap;
    public function __construct(Collection $recap)
    {
        $this->recap = $recap;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
       return $this->recap;
    }

    public function headings(): array
    {
        return[
            'Nama Karyawan',
            'Bulan',
            'Jumlah Masuk',
            'Jumlah Absen',
            'Jumlah Telat',
            'Total Telat(menit)',
            'Tipe Potongan Absen',
            'Gaji Bulan',
            'Potongan Absen',
            'Potongan Telat',
            'Potongan Kasbon',
            'Diterima',
            'Status',
            'Keterangan',
            'Metode Bayar'
        ];
    }

    public function map($row): array
    {
        return[
            $row->user->name,
            $row->recap_month,
            $row->work_day,
            $row->abstain_count,
            $row->late_day,
            $row->late_minute_count,
            $row->user->salary->fine_type ?? '',
            $row->salary_amount,
            $row->abstain_cut,
            $row->late_cut,
            $row->loan_cut,
            $row->received,
            $row->status ? 'Ya' :'Tidak',
            $row->desc,
            $row->method,
        ];
    }
}
