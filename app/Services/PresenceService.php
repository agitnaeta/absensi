<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\ScheduleDayOff;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PresenceService
{
    const TIME_ZONE = 'Asia/Jakarta';
    public function record(User $user){
        $time = Carbon::now(self::TIME_ZONE);
        return $this->writeRecord($user,$time);
    }

    public function updateCoordinate(Presence $presence, $lat, $lng){
        $presence->lat = $lat;
        $presence->lng = $lng;
        $presence->outside = $this->inCoordinate($lat,$lng);
        $presence->saveQuietly();
        Log::channel('daily_log')->info($presence->toJson());
        return $presence;
    }

    public function writeRecord(User $user, Carbon $time): Presence
    {
        $presence = Presence::where('user_id',$user->id)
                            ->whereDate('created_at',Carbon::today(self::TIME_ZONE))
                            ->first();
        if(!$presence){
            return $this->login($user, $time);
        }else{
            return $this->logout($presence,$time);
        }
    }

    public function checkIfOffDay(User $user,Carbon $now): int
    {
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
        return $days->count() > 0;
    }

    public function useOffDays(User $user)
    {
        $dayOffs = ScheduleDayOff::with('days')
            ->where('schedule_id',$user->schedule->id)
            ->get();

        $days =collect();
        $dayOffs->map(function ($dayOff) use ($days){
            $days->push(Str::lower($dayOff->days->name));
        });
        return $days;
    }

    public function login(User $user, Carbon $now): Presence
    {
       $presence = new Presence();
       $presence->user_id = $user->id;
       $presence->in = $now->format('Y-m-d H:i:s');
       $presence->save();
       return $presence;
    }
    public function logout(Presence $presence, Carbon $now): Presence
    {
        $presence->out = $now->format('Y-m-d H:i:s');
        $presence->save();
        return $presence;
    }

    public function calculateLate(Presence $presence){
        $user = User::with('schedule')
                    ->where('id',$presence->user_id)
                    ->first();
        if(!isset($user->schedule)){
            return false;
        }

        if($presence->in !== null){
            // catatan waktu masuk
            $timeIn = Carbon::createFromFormat(
                "H:i:s",
                Str::substr($user->schedule->in,0,8),
                self::TIME_ZONE
            );

            // Absen masuk
            $presenceIn = Carbon::parse($presence->in,self::TIME_ZONE);

            // tanggal & jadwal masuk
            $scheduleIn = $presenceIn->copy()->setTimeFrom($timeIn);

            if($scheduleIn->lessThan($presenceIn)){
                $presence->is_late = true;
                $presence->late_minute = $scheduleIn->diffInMinutes($presenceIn);
                $presence->saveQuietly();
            }else{
                $presence->is_late = false;
                $presence->late_minute = $scheduleIn->diffInMinutes($presenceIn);
                $presence->saveQuietly();
            }
        }
    }

    public function calculateOvertime(Presence $presence){
        $user = User::with('schedule')
                    ->where('id',$presence->user_id)
                    ->first();

        if(!isset($user->schedule)){
            return false;
        }
        $presenceIn = Carbon::parse($presence->in,self::TIME_ZONE);
        if ($this->checkIfOffDay($user, $presenceIn)){
            $presence->is_overtime = true;
            return $presence->saveQuietly();
        }

        if($presence->out !== null){
            // jadwal waktu keluar
            $overtimeIn = Carbon::createFromFormat("H:i:s",
                Str::substr($user->schedule->over_in,0,8),
                self::TIME_ZONE);

            // Absen keluar
            $presenceOut = Carbon::parse($presence->out,self::TIME_ZONE);

            // tanggal & jadwal masuk
            $scheduleOverIn = $presenceOut->copy()->setTimeFrom($overtimeIn);

            if($presenceOut->greaterThan($scheduleOverIn)){
                $presence->is_overtime = true;
                return $presence->saveQuietly();

            }
        }
    }

    function inCoordinate($latitudeFrom, $longitudeFrom) {

        $latitudeTo=(float)env('LAT');
        $longitudeTo=(float)env("LNG");

        $earthRadius = 6371000;
        // Convert degrees to radians
        $latFrom = deg2rad($latitudeFrom);
        $lonFrom = deg2rad($longitudeFrom);
        $latTo = deg2rad($latitudeTo);
        $lonTo = deg2rad($longitudeTo);

        // Calculate differences
        $latDiff = $latTo - $latFrom;
        $lonDiff = $lonTo - $lonFrom;

        // Haversine formula
        $a = sin($latDiff / 2) * sin($latDiff / 2) + cos($latFrom) * cos($latTo) * sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance in meters
        $distance = $earthRadius * $c;

        Log::channel("daily_log")->info([
            'lat'=>$latitudeFrom,
            'lng'=>$longitudeFrom,
            'lat2'=>$latitudeTo,
            'lng2'=>$longitudeTo,
            'distance'=>$distance,
        ]);
        if($distance <= 100){
            return false;
        }
        return true;
    }
}
