<?php

namespace App\Services;

use App\Models\Presence;
use App\Models\Salary;
use App\Models\SalaryRecap;
use App\Models\User;
use Carbon\Carbon;
use function Symfony\Component\Translation\t;

class SalaryServices
{

    public function recap(Presence $presence){

        if(!$this->getRecords($presence)){
            $this->createSalaryRecap($presence);
        }
    }

    protected function getRecords(Presence $presence){
        $user = User::find($presence->user_id);
        $salaryRecap = SalaryRecap::where('user_id',$user->id)
                                  ->where('recap_month',$this->recapMonth($presence))
                                  ->first();
        return $salaryRecap ?? false ;
    }
    private function recapMonth(Presence $presence){
        $recapMonth = Carbon::parse($presence->in);
        return $recapMonth->format('m-Y');
    }

    protected function createSalaryRecap(Presence $presence){
        $salaryRecap = new SalaryRecap;
        $salaryRecap->user_id = $presence->user_id;
        $salaryRecap->recap_month = $this->recapMonth($presence);
        $salaryRecap->work_day = 0;
        $salaryRecap->late_day = 0;
        $salaryRecap->salary_amount = 0;
        $salaryRecap->overtime_amount = 0;
        $salaryRecap->loan_cut = 0;
        $salaryRecap->late_cut = 0;
        $salaryRecap->abstain_cut = 0;
        $salaryRecap->received = 0;
        $salaryRecap->save();
    }

    public function calculateSalaryRecap(SalaryRecap $salaryRecap){
        $time = Carbon::createFromFormat('m-Y',$salaryRecap->recap_mont);
        $presence = Presence::whereYear('in',$time->format('Y'))
            ->whereMonth('in',$time->format('Y'))
        ->get();

        $salary = Salary::where('user_id',$salaryRecap->user_id)->first();

        $salaryRecap->work_day = $presence->count();
        $salaryRecap->late_day = $presence->sum('is_late');
        $salaryRecap->amount = $salary->amount;
        $salaryRecap->overtime_amount = $presence->sum('is_overtime') * $salary->overtime_amount;
        $salaryRecap->received = $salaryRecap->amount + $salaryRecap->overtime_amount - $salaryRecap->loan_cut - $salaryRecap->abstain_cut;
        $salaryRecap->saveQuietly();
    }


}
