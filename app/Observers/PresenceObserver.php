<?php

namespace App\Observers;

use App\Models\Presence;
use App\Services\PresenceService;
use App\Services\SalaryService;
use Illuminate\Support\Facades\Log;

class PresenceObserver
{
    protected $presenceService;
    protected $salaryService;
    public function __construct() {
        $this->presenceService = new PresenceService();
        $this->salaryService = new SalaryService();
    }

    /**
     * Handle the Presence "created" event.
     */
    public function created(Presence $presence): void
    {
        // Calculate Late
        $this->presenceService->calculateLate($presence);
        $this->presenceService->calculateOvertime($presence);
        $this->presenceService->calculateExtraTime($presence);
        $this->salaryService->recap($presence);
    }


    /**
     * Handle the Presence "updated" event.
     */
    public function updated(Presence $presence): void
    {
        // Calculate Late
        $this->presenceService->calculateLate($presence);
        $this->presenceService->calculateOvertime($presence);
        $this->presenceService->calculateExtraTime($presence);
        $this->presenceService->recalCulateCoordinate($presence);
        $this->salaryService->recap($presence);
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
