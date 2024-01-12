<?php

namespace App\Services;

class TransalateService
{

    public function salaryRecapPrefix(){
        return [
            'salary_amount'=>'Rp.',
            'overtime_amount'=>'Rp.',
            'loan_cut'=>'Rp.',
            'late_cut'=>'Rp.',
            'abstain_cut'=>'Rp.',
            'received'=>'Rp.',
        ];
    }
    public function salaryRecap(){
        return [
            'recap_month' => 'Bulan Rekap',
            'work_day' => 'Jumlah Hari Kerja',
            'late_day' => 'Jumlah Hari Terlambat',
            'salary_amount' => 'Jumlah Gaji',
            'overtime_amount' => 'Jumlah Overtime',
            'loan_cut' => 'Potongan Pinjaman',
            'late_cut' => 'Potongan Keterlambatan',
            'abstain_cut' => 'Potongan Absen',
            'received' => 'Total Diterima',
            'abstain_count' => 'Jumlah Hari Absen',
            'paid' => 'Dibayar',
            'method' => 'Metode Pembayaran',
            'desc' => 'Keterangan',
        ];
    }
}
