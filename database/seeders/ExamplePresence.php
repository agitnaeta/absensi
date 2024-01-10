<?php

namespace Database\Seeders;

use App\Models\Presence;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamplePresence extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::with('schedule')->get();
        foreach ($users as $user){

            $dateStart = Carbon::now()->subMonths(1);
            $dateEnd = Carbon::now()->addMonths(2);
            $this->inputPresence($dateStart,$dateEnd,$user);
        }
    }

    public function inputPresence(Carbon $start , Carbon $end, User $user){
        $schedule = (object)[
            "in"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->in),
            "out"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->out)->format("H:i:s"),
            "over_in"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->over_in)->format("H:i:s"),
            "over_out"=>Carbon::createFromFormat("H:i:s.u",$user->schedule->over_out)->format("H:i:s"),
        ];

        $late = rand(0,1);
        $in = $schedule->in->copy();
        if($late){
           $in =  $schedule->in->addMinutes(rand(1,10));
        }

        $presence = new Presence();
        $presence->user_id = $user->id;
        $presence->in = $start->format("Y-m-d")." ".$in->format('H:i:s');
        $presence->out = $start->format("Y-m-d")." ".$schedule->out;
        $presence->overtime_in = $start->format("Y-m-d")." ".$schedule->over_in;
        $presence->overtime_out = $start->format("Y-m-d")." ".$schedule->over_out;
        $presence->save();

        if($end->gt($start)){
            return $this->inputPresence($start->addDay(),$end,$user);
        }
        return true;
    }
}
