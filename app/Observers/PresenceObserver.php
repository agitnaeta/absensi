<?php

namespace App\Observers;

use App\Models\Presence;
use App\Models\User;
use Illuminate\Support\Carbon;

class PresenceObserver
{
    /**
     * Handle the Presence "created" event.
     */
    public function created(Presence $presence): void
    {
        $user = User::with('schedule')
            ->where('id',$presence->user_id)
            ->first();

        // Calculate Late
        $this->calculateLate($presence,$user);

    }

    public function calculateLate(Presence $presence,User $user){
        if($presence->in !== null){
            $in = Carbon::createFromFormat("H:i:s.u",$user->schedule->in);
            $presenceIn = Carbon::createFromFormat("Y-m-d H:i:s",$presence->in);
            if($in->lessThan($presenceIn)){
                $presence->is_late = true;
                $presence->late_minute = $in->diffInMinutes($presenceIn);
                $presence->saveQuietly();
            }
        }
    }

    /**
     * Handle the Presence "updated" event.
     */
    public function updated(Presence $presence): void
    {
        //
    }

    /**
     * Handle the Presence "deleted" event.
     */
    public function deleted(Presence $presence): void
    {
        //
    }

    /**
     * Handle the Presence "restored" event.
     */
    public function restored(Presence $presence): void
    {
        //
    }

    /**
     * Handle the Presence "force deleted" event.
     */
    public function forceDeleted(Presence $presence): void
    {
        //
    }
}
