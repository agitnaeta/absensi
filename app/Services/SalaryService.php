<?php

namespace App\Services;

use App\Models\LoanPayment;
use App\Models\NationalHoliday;
use App\Models\Presence;
use App\Models\Salary;
use App\Models\SalaryRecap;
use App\Models\User;
use Carbon\Carbon;
use Database\Factories\TranslateFactory;
use Illuminate\Support\Str;

class SalaryService
{

    protected $presenceService;
    public function __construct()
    {
       $this->presenceService = new PresenceService();
    }

    public function recap(Presence $presence){

        $salaryRecap  = $this->getSalaryRecapRecords($presence);
        if(!$salaryRecap){
            $this->createSalaryRecap($presence);
        }else{
            $this->calculateSalaryRecap($salaryRecap);
        }
    }

    protected function getSalaryRecapRecords(Presence $presence){
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

    public function createSalaryRecap(Presence $presence){
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
        $salaryRecap->abstain_count = 0;
        $salaryRecap->received = 0;
        $salaryRecap->save();
    }

    public function calculateSalaryRecap(SalaryRecap $salaryRecap){
        $presence = $this->getPresenceRecords($salaryRecap);
        $salary = Salary::where('user_id',$salaryRecap->user_id)->first();
        if($salary === null){
            return $salaryRecap;
        }

        $salaryRecap->work_day = $presence->count();
        $salaryRecap->late_day = $presence->sum('is_late');
        $salaryRecap->salary_amount = $salary->amount;
        $salaryRecap->overtime_amount = $presence->sum('is_overtime') * $salary->overtime_amount;
        $salaryRecap->abstain_cut = $this->unpaidLeaveDeduction($salaryRecap,$salary);
        $salaryRecap->abstain_count = $this->getAbstain($salaryRecap,$salary);
        $salaryRecap->late_minute_count = $presence->sum('late_minute');
        $salaryRecap->late_cut = $this->deductSalaryByLate($salaryRecap);
        $salaryRecap->received = $salaryRecap->salary_amount +
            $salaryRecap->overtime_amount -
            $salaryRecap->loan_cut -
            $salaryRecap->abstain_cut -
            $salaryRecap->late_cut;

        $salaryRecap->saveQuietly();

    }

    public function unpaidLeaveDeduction(SalaryRecap $salaryRecap, Salary $salary){
        $workDayInMonth = $this->workdayInAMonth($salaryRecap);

        // National Holiday
        $workDayInMonth = $workDayInMonth- $this->countOfNationalHoliday($salaryRecap);

        if($salaryRecap->work_day < $workDayInMonth){
            $abstain  = $workDayInMonth - $salaryRecap->work_day;
            return $abstain * $salary->unpaid_leave_deduction;
        }
    }

    public function getAbstain(SalaryRecap $salaryRecap,Salary $salary){
        $workDayInMonth = $this->workdayInAMonth($salaryRecap);
        return $workDayInMonth - $salaryRecap->work_day;
    }

    public function testSalaryRecap(){
        $sr = SalaryRecap::find(1);
        return $this->workdayInAMonth($sr);
    }
    public function offDayInMonth(SalaryRecap $salaryRecap){
        $month = $this->getRecapMonthCarbon($salaryRecap);
        $user = User::with('schedule')->find($salaryRecap->user_id);
        $useOffDays = $this->presenceService->useOffDays($user);
        $startMonth = $month->copy()->startOfMonth();
        $endMonth = $month->copy()->endOfMonth();
        $countOffDay = collect();

        for ($currentDay = $startMonth; $currentDay->lte($endMonth); $currentDay->addDay()) {
            $currentDay->locale('id_ID')->isoFormat('dddd');

            if ($useOffDays->contains(Str::lower($currentDay->dayName))) {
                $countOffDay->push($currentDay->dayName);
            }
        }

        return $countOffDay->count();
    }

    public function workdayInAMonth(SalaryRecap $salaryRecap){
        $month = $this->getRecapMonthCarbon($salaryRecap);
        return $month->daysInMonth - $this->offDayInMonth($salaryRecap);
    }

    public function getPresenceRecords(SalaryRecap $salaryRecap){
        $time = $this->getRecapMonthCarbon($salaryRecap);
        return  Presence::where('user_id',$salaryRecap->user_id)
            ->whereYear('in',$time->format('Y'))
            ->whereMonth('in',$time->format('m'))
            ->get();
    }

    public function getRecapMonthCarbon(SalaryRecap $salaryRecap)
    {
        return Carbon::createFromFormat('m-Y',$salaryRecap->recap_month);
    }

    public function countOfNationalHoliday(SalaryRecap$salaryRecap){
        $time = $this->getRecapMonthCarbon($salaryRecap);
        $nationalHoliday = NationalHoliday::whereMonth('date',$time->month)
            ->whereYear('date',$time->year)->get();
        return $nationalHoliday->count();
    }


    function payLoan(SalaryRecap $salaryRecap){
        if($salaryRecap->loan_cut > 0){
            // Check if a LoanPayment with the given salary_recap_id already exists
            $existingLoanPayment = LoanPayment::where('salary_recap_id', $salaryRecap->id)->first();

            if (!$existingLoanPayment) {
                // If no existing LoanPayment, create and save a new one
                $loanPayment = new LoanPayment();

                $loanPayment->user_id = $salaryRecap->user_id;
                $loanPayment->salary_recap_id = $salaryRecap->id;
                $loanPayment->amount = $salaryRecap->loan_cut;
                $loanPayment->date = $salaryRecap->updated_at;

                $loanPayment->save();
            } else {
                $existingLoanPayment->update([
                    'user_id' => $salaryRecap->user_id,
                    'amount' => $salaryRecap->loan_cut,
                    'date' => $salaryRecap->updated_at,
                ]);
            }


        }
    }

    public function removeLoanPayment(SalaryRecap $salaryRecap){
            LoanPayment::where('salary_recap_id',$salaryRecap->id)
                ->first()->delete();
    }

    public function deductSalaryByLate(SalaryRecap $salaryRecap){
        $user = User::with('salary')->find($salaryRecap->user_id);
        if($user->salary->type  === TranslateFactory::MINUTE){
            return $user->salary->fine_per_minute * $salaryRecap->late_minute_count;
        }
        else
        {
            return $user->salary->fine * $salaryRecap->late_day;
        }
    }

}
