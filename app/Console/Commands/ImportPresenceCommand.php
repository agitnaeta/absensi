<?php

namespace App\Console\Commands;

use App\Imports\PresenceImport;
use App\Models\Presence;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
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

        $data = collect($data);
        $data->map(function ($value){
            $presences = collect($value);
            $carbon = Carbon::now()->startOfMonth();
            $presences->each(function ($v,$i) use($presences,$carbon){
                if($i != 0 && $i %2!==0 ){
                  $value = $v + $presences[$i+1];
                  $day =$carbon->addDay();
                  $this->import($day,$presences[0],$value);
                }
            });
        });

    }

    public function import(Carbon $start, string $userId,  $value){
        $start = $start->copy()->subDay();
        if($value < 1){
            return false;
        }
        $hourIn = Carbon::createFromTime(8,0);
        $hourOut = Carbon::createFromTime(17,0);

        $oldRecord = Presence::where('user_id',$userId)
            ->whereDate('in',$start)
            ->first();
        if($oldRecord!=null){
            $oldRecord->delete();
        }

        $presence = new Presence();
        $presence->user_id = $userId;
        $presence->in = $start->setTimeFrom($hourIn)->format("Y-m-d H:i:s");

        $out = $start->copy()->setTimeFrom($hourOut);
        if($value > 1){
            $presence->out = $out->addHours(2)->format('Y-m-d H:i:s');
        }else{
            $presence->out = $out->format("Y-m-d H:i:s");
        }

        $this->info($presence->toJson());
        $presence->save();
    }
}
