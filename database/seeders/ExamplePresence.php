<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\SalaryRecap;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ExamplePresence extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Presence::truncate();
        SalaryRecap::truncate();
        $users = User::with('schedule')->get();
        foreach ($users as $user){
            $dateStart = Carbon::now()->startOfMonth();
            $dateEnd = Carbon::now()->endOfMonth();
            $this->inputPresence($dateStart,$dateEnd,$user);
        }
    }

    public function inputPresence(Carbon $start , Carbon $end, User $user){
        $schedule = (object)[
            "in"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->in),
            "out"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->out),
        ];

        $late = rand(0,1);
        $in = $schedule->in->copy();
        if($late){
           $in =  $in->addMinutes(rand(1,10));
        }

        $overtime = rand(1,0);
        $out = $schedule->out->copy();
        if($overtime){
            $out =  $out->addMinutes(rand(1,60));
        }

        $presence = new Presence();
        $presence->user_id = $user->id;
        $presence->in = $start->format("Y-m-d")." ".$in->format('H:i:s');
        $presence->out = $start->format("Y-m-d")." ".$out->format('H:i:s');
        $presence->save();

        if($end->gt($start)){
            return $this->inputPresence($start->addDay(),$end,$user);
        }
        return true;
    }
}
