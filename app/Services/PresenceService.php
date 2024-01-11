<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\ScheduleDayOff;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;

class PresenceService
{

    public function __construct(){}

    public function record(User $user){

        $now = Carbon::now();

        $isOffDay = $this->checkIfOffDay($user,$now);
        if($isOffDay){
            return $this->recordOnOffDay($user,$now);
        }
        return $this->recordOnOffDay($user,$now);
    }

    public function checkIfOffDay(User $user,Carbon $now){
        $dayOffs = ScheduleDayOff::with('days')
            ->where('schedule_id',$user->schedule->id)
            ->get();

        $days =collect();
        $dayOffs->map(function ($dayOff) use ($days,$now){
            $now->locale('id_ID');
            $day = $now->isoFormat('dddd');
            if(Str::lower($day) == Str::lower($dayOff->days->name)){
                $days->push($dayOff);
            }
        });
        return $days->count();
    }

    public function recordOnOffDay(User $user,Carbon $now){
        $presence = Presence::where('user_id',$user->id)
            ->whereDate('created_at',Carbon::today())
            ->first();
        if(!$presence){
            return $this->overtimeLogin($user,$now);
        }
        // jika sudah pernah
        $presence->overtime_out = $now->format('Y-m-d H:i:s');
        $presence->save();
        return $presence;

    }
    public function recordOnDaily(User $user, Carbon $now){
        $presence = Presence::where('user_id',$user->id)
            ->whereDate('created_at',Carbon::today())
            ->first();

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

    public function overtimeLogin(User $user, Carbon $now){
        $p = new Presence();
        $p->user_id = $user->id;
        $p->is_overtime = true;
        $p->overtime_in = $now->format('Y-m-d H:i:s');
        $p->save();
        return $p;
    }
}
