<?php

namespace App\Observers;

use App\Models\SalaryRecap;
use App\Services\SalaryService;

class SalaryRecapObserver
{
    protected $salaryService;
    public function __construct()
    {
        $this->salaryService = new SalaryService();
    }

    /**
     * Handle the SalaryRecap "created" event.
     */
    public function created(SalaryRecap $salaryRecap): void
    {
        $this->salaryService->calculateSalaryRecap($salaryRecap);
    }

    /**
     * Handle the SalaryRecap "updated" event.
     */
    public function updated(SalaryRecap $salaryRecap): void
    {
        $this->salaryService->calculateSalaryRecap($salaryRecap);
    }

    /**
     * Handle the SalaryRecap "deleted" event.
     */
    public function deleted(SalaryRecap $salaryRecap): void
    {
        //
    }

    /**
     * Handle the SalaryRecap "restored" event.
     */
    public function restored(SalaryRecap $salaryRecap): void
    {
        //
    }

    /**
     * Handle the SalaryRecap "force deleted" event.
     */
    public function forceDeleted(SalaryRecap $salaryRecap): void
    {
        //
    }
}
