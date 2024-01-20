<?php

namespace App\Imports;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Concerns\ToCollection;

abstract class PresenceImport implements ToCollection
{
    public function recursiveInput(Carbon $start, Carbon $end, User $user,$overtime){

        $hourIn = Carbon::createFromTime(8,0);
        $hourOut = Carbon::createFromTime(17,0);
        $presence = new Presence();
        $presence->user_id = $user->id;
        $presence->in = $start->setTimeFrom($hourIn)->format("Y-m-d H:i:s");

        $out = $start->copy()->setTimeFrom($hourOut);
        if($overtime === true){
            $presence->out = $out->addHours(2)->format('Y-m-d H:i:s');
        }else{
            $presence->out = $out->format("Y-m-dH:i:s");
        }
        $presence->save();
        if($start->lessThan($end)){
            $nextDay = $start->copy()->addDay();
            return $this->recursiveInput($nextDay,$end,$user);
        }
    }
}
