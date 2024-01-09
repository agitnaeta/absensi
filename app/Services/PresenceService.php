<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PresenceService
{

    public function __construct(){}

    public function record(User $user){
        $presence = Presence::where('user_id',$user->id)
            ->whereDate('created_at',Carbon::today())
            ->first();

        $now = Carbon::now();

        // Jika Hari normal
        $this->recordOnDaily($presence,$user,$now);

        // Jika Hari libur

    }
    public function recordOnDaily(Presence $presence,User $user, Carbon $now){
        // shift hour
        $in = Carbon::createFromFormat("H:i:s.u",$user->schedule->in);
        $oIn = Carbon::createFromFormat("H:i:s.u",$user->schedule->over_in);

        if(!$presence){
            return $this->login(new Presence(),$user);
        }

        // schema Logout
        if($presence && $now->greaterThan($in)){
            $presence->out = Carbon::now()->format('Y-m-d H:i:s');
        }

        // Overtime Record
        if($presence && $now->greaterThan($oIn)){
            if($presence->overtime_in == null){
                $presence->overtime_in = Carbon::now()->format('Y-m-d H:i:s');
            }else{
                $presence->overtime_out = Carbon::now()->format('Y-m-d H:i:s');
            }
            $presence->is_overtime = 1;
            $presence->is_overtime = 1;
        }
        $presence->save();
        return $presence;
    }

    public function login(Presence $p, User $user, Carbon $now){
        $in = Carbon::createFromFormat("H:i:s.u",$user->schedule->in);
        $p->user_id = $user->id;
        $p->in = $now->format('Y-m-d H:i:s');
        if($now->greaterThan($in)){
            $p->is_late = true;
            $p->late_minute = $now->diffInMinutes($in);
        }
        $p->save();
        return $p;
    }
}
