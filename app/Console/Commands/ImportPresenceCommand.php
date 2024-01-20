<?php

namespace App\Console\Commands;

use App\Imports\PresenceImport;
use Illuminate\Console\Command;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportPresenceCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:presence-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import kehadiran';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $storagePath = storage_path("/app/public/recap_absen_januari.xlsx");
        $data = IOFactory::load($storagePath,0,[
            IOFactory::READER_XLSX
        ])->getActiveSheet()->toArray();
        $dataCollection = collect($data);
        $monthCollection = collect();
        $dataCollection->map(function ($presences) use ($monthCollection) {
            $presences = collect($presences);
            $dayCollection = collect(['name'=>$presences[0],'login'=>collect()]);
            $presences->each(function ($value,$key) use($dayCollection){
                if ($key % 2 == 0) {
                    $dayCollection['login']->push(['masuk']);
                    $this->info("Item at index $key: $value\n");
                }
            });
            $monthCollection->push($dayCollection);
        });

    }
}
