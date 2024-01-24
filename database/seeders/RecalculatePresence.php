<?php

namespace Database\Seeders;

use App\Models\Presence;
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
        $presences = Presence::all();
        $presences->map(function ($presence){
            $presence->outside = true;
            $presence->save();
        });

    }
}
