<?php

namespace App\Services;

use App\Models\LoanPayment;
use App\Models\NationalHoliday;
use App\Models\Presence;
use App\Models\Salary;
use App\Models\SalaryRecap;
use App\Models\User;
use App\Services\Acc\Acc;
use App\Services\Acc\AccTransaction;
use Carbon\Carbon;
use Database\Factories\TranslateFactory;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SalaryService
{

    protected $presenceService;


    protected $transactionService;

    public function __construct()
    {
       $this->presenceService = new PresenceService();
       $this->transactionService = new TransactionService(new Acc(), new AccTransaction());

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
        if($user==null){
            return false;
        }
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
        $user = User::find($salaryRecap->user_id);
        if($salary === null || $user === null){
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
        $salaryRecap->extra_time = $presence->sum('extra_time');
        $salaryRecap->extra_time_amount = $this->calculateExtraTimeAmount($salaryRecap);
        $salaryRecap->received = $salaryRecap->salary_amount +
            $salaryRecap->overtime_amount -
            $salaryRecap->loan_cut -
            $salaryRecap->abstain_cut -
            $salaryRecap->late_cut + $salaryRecap->extra_time_amount;

        $salaryRecap->saveQuietly();


        // only update when there's a payment
        // avoid Issue always recall
        if($salaryRecap->paid){
            $this->transactionService->updateRecordSalaryToACC($salaryRecap);

        }
    }

    public function unpaidLeaveDeduction(SalaryRecap $salaryRecap, Salary $salary){
        $workDayInMonth = $this->workdayInAMonth($salaryRecap);

        // National Holiday
        $workDayInMonth = $workDayInMonth- $this->countOfNationalHoliday($salaryRecap);

        if($salaryRecap->work_day < $workDayInMonth){
            $abstain  = $workDayInMonth - $salaryRecap->work_day;
            return $abstain * $salary->unpaid_leave_deduction;
        }
        return 0;
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

                // only update when there's a payment
                // avoid Issue always recall
                if($salaryRecap->paid){
                    $this->transactionService->updateRecordPayLoanACC($loanPayment);
                }
            } else {
                $existingLoanPayment->update([
                    'user_id' => $salaryRecap->user_id,
                    'amount' => $salaryRecap->loan_cut,
                    'date' => $salaryRecap->updated_at,
                ]);

                // only update when there's a payment
                // avoid Issue always recall
                if($salaryRecap->paid){
                    $this->transactionService->updateRecordPayLoanACC($existingLoanPayment);
                }
            }
        }
        else{
            $loanPayment = LoanPayment::where("salary_recap_id",$salaryRecap->id)
                ->first();
            if($loanPayment){
                $this->transactionService->deleteRecordPayLoanAcc($loanPayment);
                $loanPayment->delete();
            }
        }
    }

    public function removeLoanPayment(SalaryRecap $salaryRecap){
            $loan = LoanPayment::where('salary_recap_id',$salaryRecap->id)
                ->first();

            $this->transactionService->deleteRecordPayLoanAcc($loan);
            $loan->delete();


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
    public function calculateExtraTimeAmount($salaryRecap){
        // Extra time x salary extra time
        $user = User::with('salary')->find($salaryRecap->user_id);
        if($user->salary->extra_time_rule == 1){
            return $user->salary->extra_time * $salaryRecap->extra_time;
        }
        return 0;
    }

    public function deleteWhenUncheck(SalaryRecap $salaryRecap)
    {
        // check if salary recap have acc id
        if($salaryRecap->acc_id && $salaryRecap->paid == 0 )
        {
            // Delete loan payment related
            $loanPayment = LoanPayment::where("salary_recap_id",$salaryRecap->id)
                                      ->first();
            if($loanPayment){
                $this->transactionService->deleteRecordPayLoanAcc($loanPayment);
                $loanPayment->delete();
            }


            // Delete salary recap
            $this->transactionService->deleteRecordSalaryToACC($salaryRecap);

            $salaryRecap->acc_id= NULL;
            $salaryRecap->saveQuietly();

        }
    }




}
