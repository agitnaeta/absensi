<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TranslateFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {

    }

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
