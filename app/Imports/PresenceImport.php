<?php

namespace App\Imports;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Support\Carbon;

abstract class PresenceImport
{
    public function import(Carbon $start, string $userId,  $value){

        $hourIn = Carbon::createFromTime(8,0);
        $hourOut = Carbon::createFromTime(17,0);
        $presence = new Presence();
        $presence->user_id = $userId;
        $presence->in = $start->setTimeFrom($hourIn)->format("Y-m-d H:i:s");

        $out = $start->copy()->setTimeFrom($hourOut);
        if($value > 1){
            $presence->out = $out->addHours(2)->format('Y-m-d H:i:s');
        }else{
            $presence->out = $out->format("Y-m-dH:i:s");
        }
        $presence->save();
    }
}
