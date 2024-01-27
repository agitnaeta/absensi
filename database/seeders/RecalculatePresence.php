<?php

namespace Database\Seeders;

use App\Models\Presence;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

class RecalculatePresence extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        $presences = Presence::whereMonth("created_at",$now->month)
            ->whereYear("created_at",$now->year)
            ->get();
        $presences->map(function ($presence){
            $presence->outside = false;
            $presence->save();
        });

    }
}
