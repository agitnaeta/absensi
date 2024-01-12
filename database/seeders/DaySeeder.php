<?php

namespace Database\Seeders;

use App\Models\Day;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = collect(['Senin','Selasa','Sabu','Kamis','Jumat','Sabtu','Minggu']);
        foreach ($days as $day){
            $_day = Day::whereRaw('LOWER(name) = ?', [strtolower($day)])->first();
            if($_day === null){
                $_day = new Day();
                $_day->name = $day;
                $_day->saveQuietly();
            }
        }

    }
}
