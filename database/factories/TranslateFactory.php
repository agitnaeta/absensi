<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class TranslateFactory extends Factory
{

    const MINUTE ='minute';
    const FLAT ='flat';
    const HOUR='jam';
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
            'late_minute_count' => 'Menit Telat',
        ];
    }


    public function schedules(){
        return   [
            'name' => 'Nama',
            'in' => 'Masuk',
            'out' => 'Keluar',
            'over_in' => 'Lembur Masuk',
            'over_out' => 'Lembur Keluar',
//            'fine_per_minute' => 'Denda per Menit',
        ];

    }
    public function presences(){
        return   [
            'in' => 'Masuk',
            'out' => 'Keluar',
            'is_overtime' => 'Lembur',
            'is_late' => 'Telat',
            'late_minute' => 'Telat(menit)',
            'lat' => 'Latitude',
            'lng' => 'Longitude',
            'outside' => 'Kantor',
        ];

    }

    public function nationalHoliday(){
        return [
            'date'=>'Tanggal'
        ];
    }
    public function loan(){
        return [
            'date'=>'Tanggal',
            'amount'=>'Jumlah'
        ];
    }

    public function company(){
        return[
            'name'=>'nama',
            'image'=>'Logo Perusaan',
            'address'=>'Alamat',
            'phone'=>'telepon',
            'id_card'=>'ID Card Background',
        ];
    }
}
