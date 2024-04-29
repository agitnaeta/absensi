<?php

namespace App\Console\Commands;

use App\Services\SalaryService;
use App\Models\SalaryRecap;
use Illuminate\Console\Command;

class CalculateSalary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'calculate:salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculating Salary';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $recaps = SalaryRecap::all();
        foreach ($recaps as $recap) {
            (new SalaryService())->calculateSalaryRecap($recap);
        }
        return $this->info("Done");
    }
}
